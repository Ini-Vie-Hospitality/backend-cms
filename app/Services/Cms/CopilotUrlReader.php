<?php

namespace App\Services\Cms;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class CopilotUrlReader
{
    private const MAX_BYTES = 1048576;

    private const MAX_CONTEXT = 12000;

    /** @return array{url: string, title: string, content: string} */
    public function read(string $prompt): ?array
    {
        preg_match('/https?:\/\/[^\s<>"\']+/i', $prompt, $match);

        if (! isset($match[0])) {
            return null;
        }

        $currentUrl = rtrim($match[0], ".,;)]}'\"");
        $response = null;

        for ($redirects = 0; $redirects <= 3; $redirects++) {
            $pinnedIp = $this->assertPublicHttps($currentUrl);
            $host = parse_url($currentUrl, PHP_URL_HOST);
            $response = Http::timeout(10)
                ->withHeaders(['Accept' => 'text/html,application/xhtml+xml,text/plain', 'User-Agent' => 'IniVieCMS-Copilot/1.0'])
                ->withOptions([
                    'allow_redirects' => false,
                    'curl' => [CURLOPT_RESOLVE => ["{$host}:443:{$pinnedIp}"]],
                    'on_headers' => function ($response): void {
                        if ((int) $response->getHeaderLine('Content-Length') > self::MAX_BYTES) {
                            throw new RuntimeException('The linked website exceeds the size limit.');
                        }
                    },
                    'progress' => function (int $downloadBytes): void {
                        if ($downloadBytes > self::MAX_BYTES) {
                            throw new RuntimeException('The linked website exceeds the size limit.');
                        }
                    },
                ])
                ->get($currentUrl);

            if ($response->redirect(false) && $location = $response->header('Location')) {
                $currentUrl = $this->resolveLocation($currentUrl, $location);

                continue;
            }

            break;
        }

        if (! $response || ! $response->successful()) {
            throw new RuntimeException('The linked website could not be read.');
        }

        $contentType = (string) $response->header('Content-Type');

        if (! preg_match('#^(text/html|application/xhtml|text/plain)#i', $contentType)) {
            throw new RuntimeException('Only HTML or plain-text websites can be read.');
        }

        $body = substr($response->body(), 0, self::MAX_BYTES);
        [$title, $content] = $this->extract($body);

        return ['url' => $currentUrl, 'title' => $title ?: parse_url($currentUrl, PHP_URL_HOST), 'content' => Str::limit($content, self::MAX_CONTEXT, '')];
    }

    private function assertPublicHttps(string $url): string
    {
        $parts = parse_url($url);
        $host = $parts['host'] ?? '';

        if (($parts['scheme'] ?? '') !== 'https' || $host === '' || filter_var($host, FILTER_VALIDATE_IP)) {
            throw new RuntimeException('Copilot can only read a public HTTPS website.');
        }

        $records = @dns_get_record($host, DNS_A | DNS_AAAA);

        if ($records === false || $records === []) {
            throw new RuntimeException('The website host could not be resolved.');
        }

        foreach ($records as $record) {
            $ip = $record['ipv6'] ?? $record['ip'] ?? '';

            if ($ip === '' || filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
                throw new RuntimeException('Private and reserved website hosts are not allowed.');
            }

            return $ip;
        }

        throw new RuntimeException('The website host could not be resolved.');
    }

    private function resolveLocation(string $base, string $location): string
    {
        $relative = @parse_url($location);

        if ($relative === false || str_starts_with($location, '//')) {
            throw new RuntimeException('The website returned an invalid redirect.');
        }

        return str_starts_with($location, '/')
            ? preg_replace('#^(https://[^/]+).*$#i', '$1'.$location, $base)
            : $location;
    }

    /** @return array{0:string, 1:string} */
    private function extract(string $html): array
    {
        preg_match('#<title[^>]*>(.*?)</title>#is', $html, $title);
        $content = preg_replace('#<(script|style|noscript)[^>]*>.*?</\1>#is', '', $html) ?? '';
        $content = strip_tags(preg_replace('#<(br|/p|/div|/h[1-6]|/li)[^>]*>#i', "\n", $content));
        $content = html_entity_decode($content, ENT_QUOTES | ENT_HTML5);

        return [trim(html_entity_decode($title[1] ?? '', ENT_QUOTES | ENT_HTML5)), trim((string) preg_replace("/[ \t]+/", ' ', $content))];
    }
}
