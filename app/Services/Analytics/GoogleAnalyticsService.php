<?php

namespace App\Services\Analytics;

use Closure;
use Google\Analytics\Data\V1beta\Client\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\OrderBy;
use Google\Analytics\Data\V1beta\OrderBy\DimensionOrderBy;
use Google\Analytics\Data\V1beta\OrderBy\MetricOrderBy;
use Google\Analytics\Data\V1beta\RunReportRequest;
use Google\Analytics\Data\V1beta\RunReportResponse;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Cache;
use RuntimeException;
use Throwable;

class GoogleAnalyticsService
{
    private const SCOPE = 'https://www.googleapis.com/auth/analytics.readonly';

    private Closure $runReport;

    public function __construct(?Closure $runReport = null)
    {
        $this->runReport = $runReport ?? fn (RunReportRequest $request): RunReportResponse => $this->createClient()->runReport($request);
    }

    /**
     * @return array<string, mixed>
     */
    public function dashboard(): array
    {
        $cacheKey = 'google-analytics.dashboard.'.config('services.google_analytics.property_id');

        try {
            return Cache::remember(
                $cacheKey,
                now()->addMinutes(max(1, (int) config('services.google_analytics.cache_minutes', 5))),
                fn (): array => $this->loadDashboard(),
            );
        } catch (Throwable $exception) {
            report($exception);

            return [
                'status' => 'unavailable',
                'period' => $this->period(),
                'metrics' => [],
                'traffic' => [],
                'devices' => [],
                'sources' => [],
                'pages' => [],
                'events' => [],
                'error' => 'Google Analytics data is temporarily unavailable.',
            ];
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function loadDashboard(): array
    {
        $period = $this->period();
        $current = $this->dateRange($period['start'], $period['end']);
        $previous = $this->dateRange($period['previous_start'], $period['previous_end']);

        $metricNames = ['activeUsers', 'sessions', 'screenPageViews', 'engagementRate'];
        $currentMetrics = $this->values($this->report([], $metricNames, [$current]));
        $previousMetrics = $this->values($this->report([], $metricNames, [$previous]));

        return [
            'status' => 'ok',
            'period' => $period,
            'metrics' => [
                'users' => $this->metric('Total users', $currentMetrics[0] ?? 0, $previousMetrics[0] ?? 0, 'number'),
                'sessions' => $this->metric('Sessions', $currentMetrics[1] ?? 0, $previousMetrics[1] ?? 0, 'number'),
                'views' => $this->metric('Page views', $currentMetrics[2] ?? 0, $previousMetrics[2] ?? 0, 'number'),
                'engagement_rate' => $this->metric('Engagement rate', $currentMetrics[3] ?? 0, $previousMetrics[3] ?? 0, 'percent'),
            ],
            'traffic' => $this->rows($this->report(['date'], ['sessions'], [$current], 100, $this->dimensionOrder('date'))),
            'devices' => $this->rows($this->report(['deviceCategory'], ['activeUsers'], [$current], 10, $this->metricOrder('activeUsers'))),
            'sources' => $this->rows($this->report(['sessionSource'], ['sessions'], [$current], 10, $this->metricOrder('sessions'))),
            'pages' => $this->rows($this->report(['pagePath'], ['screenPageViews', 'engagementRate'], [$current], 10, $this->metricOrder('screenPageViews'))),
            'events' => $this->rows($this->report(['eventName'], ['eventCount', 'totalUsers'], [$current], 10, $this->metricOrder('eventCount'))),
            'error' => null,
        ];
    }

    private function createClient(): BetaAnalyticsDataClient
    {
        $credentials = config('services.google_analytics.credentials');
        $propertyId = trim((string) config('services.google_analytics.property_id'));

        if (! is_string($credentials) || trim($credentials) === '' || $propertyId === '') {
            throw new RuntimeException('Google Analytics credentials and property ID are required.');
        }

        $credentialPath = is_file($credentials) ? $credentials : base_path($credentials);

        if (! is_file($credentialPath)) {
            throw new RuntimeException('Google Analytics credential file was not found.');
        }

        return new BetaAnalyticsDataClient([
            'credentials' => new ServiceAccountCredentials(self::SCOPE, $credentialPath),
            'transport' => 'rest',
        ]);
    }

    /**
     * @param  list<string>  $dimensions
     * @param  list<string>  $metrics
     * @param  list<DateRange>  $dateRanges
     * @param  list<OrderBy>  $orderBys
     */
    private function report(array $dimensions, array $metrics, array $dateRanges, int $limit = 10000, array $orderBys = []): RunReportResponse
    {
        $dimensionObjects = array_map(fn (string $name): Dimension => new Dimension(['name' => $name]), $dimensions);
        $metricObjects = array_map(fn (string $name): Metric => new Metric(['name' => $name]), $metrics);

        return ($this->runReport)(new RunReportRequest([
            'property' => 'properties/'.trim((string) config('services.google_analytics.property_id')),
            'dimensions' => $dimensionObjects,
            'metrics' => $metricObjects,
            'date_ranges' => $dateRanges,
            'limit' => $limit,
            'order_bys' => $orderBys,
        ]));
    }

    private function dateRange(string $start, string $end): DateRange
    {
        return new DateRange(['start_date' => $start, 'end_date' => $end]);
    }

    /**
     * @return list<float>
     */
    private function values(RunReportResponse $response): array
    {
        $row = iterator_to_array($response->getRows())[0] ?? null;

        return $row ? array_values(array_map(fn (object $value): float => (float) $value->getValue(), iterator_to_array($row->getMetricValues()))) : [];
    }

    /**
     * @return list<array{dimensions: list<string>, metrics: list<float>}>
     */
    private function rows(RunReportResponse $response): array
    {
        return array_values(array_map(function (object $row): array {
            return [
                'dimensions' => array_values(array_map(fn (object $value): string => $value->getValue(), iterator_to_array($row->getDimensionValues()))),
                'metrics' => array_values(array_map(fn (object $value): float => (float) $value->getValue(), iterator_to_array($row->getMetricValues()))),
            ];
        }, iterator_to_array($response->getRows())));
    }

    /**
     * @return array{label: string, value: float, change: float|null, format: string}
     */
    private function metric(string $label, float $value, float $previous, string $format): array
    {
        $change = $previous == 0.0 ? null : (($value - $previous) / $previous) * 100;

        return compact('label', 'value', 'change', 'format');
    }

    /**
     * @return list<OrderBy>
     */
    private function dimensionOrder(string $name): array
    {
        return [new OrderBy(['dimension' => new DimensionOrderBy(['dimension_name' => $name])])];
    }

    /**
     * @return list<OrderBy>
     */
    private function metricOrder(string $name): array
    {
        return [new OrderBy(['metric' => new MetricOrderBy(['metric_name' => $name]), 'desc' => true])];
    }

    /**
     * @return array{start: string, end: string, previous_start: string, previous_end: string}
     */
    private function period(): array
    {
        return [
            'start' => now()->subDays(29)->format('Y-m-d'),
            'end' => now()->format('Y-m-d'),
            'previous_start' => now()->subDays(59)->format('Y-m-d'),
            'previous_end' => now()->subDays(30)->format('Y-m-d'),
        ];
    }
}
