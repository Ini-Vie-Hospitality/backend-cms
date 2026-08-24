<?php

declare(strict_types=1);

const BASE_URL = 'https://inivie.com';
const SITEMAP_URL = BASE_URL.'/sitemap-0.xml';
const MAX_CONTENT_LENGTH = 20000;
const CONCURRENCY = 10;

$output = $argv[1] ?? __DIR__.'/../database/data/inivie-knowledge.json';
$urls = sitemapUrls(fetch(SITEMAP_URL));
$entries = [];

foreach (array_chunk($urls, CONCURRENCY) as $index => $chunk) {
    foreach (fetchMany($chunk) as $url => $html) {
        $entry = extractKnowledge($url, $html);
        if ($entry !== null) {
            $entries[$entry['source_url']] = $entry;
        }
    }

    fwrite(STDERR, sprintf("Crawled %d/%d URLs\n", min(($index + 1) * CONCURRENCY, count($urls)), count($urls)));
}

ksort($entries);
$directory = dirname($output);
if (! is_dir($directory) && ! mkdir($directory, 0777, true) && ! is_dir($directory)) {
    throw new RuntimeException("Unable to create output directory: {$directory}");
}

file_put_contents($output, json_encode(array_values($entries), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR).PHP_EOL);
fwrite(STDERR, sprintf("Saved %d knowledge entries to %s\n", count($entries), $output));

/** @return list<string> */
function sitemapUrls(string $xml): array
{
    preg_match_all('~<loc>(https://inivie\.com/[^<]*)</loc>~', $xml, $matches);

    return array_values(array_unique(array_filter(array_map(
        fn (string $url): string => rtrim(html_entity_decode($url), '/'),
        $matches[1],
    ), fn (string $url): bool => ! shouldSkip($url))));
}

function shouldSkip(string $url): bool
{
    $path = parse_url($url, PHP_URL_PATH) ?: '/';

    return preg_match('~/(contact|gallery|salesinquiry|subscribe)(/|$)|^/(new-layout|undefined)(/|$)~i', $path) === 1;
}

/** @param list<string> $urls
 * @return array<string, string>
 */
function fetchMany(array $urls): array
{
    $multi = curl_multi_init();
    $handles = [];

    foreach ($urls as $url) {
        $handle = curl_init($url);
        curl_setopt_array($handle, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_USERAGENT => 'IniVieKnowledgeSeeder/1.0',
            CURLOPT_HTTPHEADER => ['Accept: text/html,application/xhtml+xml'],
        ]);
        curl_multi_add_handle($multi, $handle);
        $handles[$url] = $handle;
    }

    do {
        $status = curl_multi_exec($multi, $active);
        if ($active) {
            curl_multi_select($multi, 1);
        }
    } while ($active && $status === CURLM_OK);

    $responses = [];
    foreach ($handles as $url => $handle) {
        $body = curl_multi_getcontent($handle);
        if (curl_getinfo($handle, CURLINFO_RESPONSE_CODE) === 200 && is_string($body)) {
            $responses[$url] = $body;
        }
        curl_multi_remove_handle($multi, $handle);
    }
    curl_multi_close($multi);

    return $responses;
}

function fetch(string $url): string
{
    $handle = curl_init($url);
    curl_setopt_array($handle, [CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true, CURLOPT_TIMEOUT => 30]);
    $body = curl_exec($handle);
    $status = curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
    if (! is_string($body) || $status !== 200) {
        throw new RuntimeException("Unable to fetch {$url}");
    }

    return $body;
}

/** @return array{title: string, category: string, content: string, source_url: string}|null */
function extractKnowledge(string $url, string $html): ?array
{
    $dom = new DOMDocument;
    libxml_use_internal_errors(true);
    if (! $dom->loadHTML($html)) {
        return null;
    }

    $xpath = new DOMXPath($dom);
    $root = $xpath->query('//article')->item(0) ?? $xpath->query('//main')->item(0) ?? $xpath->query('//body')->item(0);
    if ($root === null) {
        return null;
    }

    $parts = [];
    foreach ($xpath->query('.//h1|.//h2|.//h3|.//h4|.//p|.//li|.//th|.//td', $root) ?: [] as $node) {
        $text = cleanText($node->textContent);
        if (mb_strlen($text) >= 20 && ! isBoilerplate($text)) {
            $parts[$text] = true;
        }
    }

    $content = mb_substr(implode("\n", array_keys($parts)), 0, MAX_CONTENT_LENGTH);
    if (mb_strlen($content) < 120) {
        return null;
    }

    $title = cleanText((string) $xpath->evaluate('string((//h1)[1])'));
    if ($title === '') {
        $title = cleanText((string) $xpath->evaluate('string(//title)'));
    }
    $title = preg_replace('~\s*[-|]\s*(Blog\s*\|\s*)?iNi ViE Hospitality.*$~iu', '', $title) ?: $title;
    if (mb_strtolower($title) === 'ini vie hospitality') {
        $title = titleFromUrl($url);
    }

    return [
        'title' => mb_substr($title, 0, 180),
        'category' => categoryFor($url),
        'content' => $content,
        'source_url' => $url,
    ];
}

function cleanText(string $text): string
{
    return trim(preg_replace('/\s+/u', ' ', html_entity_decode($text, ENT_QUOTES | ENT_HTML5)) ?? '');
}

function isBoilerplate(string $text): bool
{
    return preg_match('~^(Book Now|Home|Press Hub|Discover Bali|Read More|Learn More|View More|Explore More|Back to Top)$~i', $text) === 1;
}

function categoryFor(string $url): string
{
    $path = trim((string) parse_url($url, PHP_URL_PATH), '/');
    $segment = explode('/', $path)[0] ?? '';

    return match ($segment) {
        '' => 'General',
        'blog' => 'Blog',
        'discover-bali' => 'Discover Bali',
        'offers', 'special-offers' => 'Offers',
        'brand' => 'Brand',
        'about' => 'About',
        'press-hub' => 'Press',
        default => 'Properties & Experiences',
    };
}

function titleFromUrl(string $url): string
{
    $path = trim((string) parse_url($url, PHP_URL_PATH), '/');
    $slug = basename($path);

    return ucwords(str_replace(['-', '_'], ' ', $slug));
}
