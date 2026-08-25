<?php

namespace Tests\Unit;

use App\Services\Analytics\GoogleAnalyticsService;
use Google\Analytics\Data\V1beta\DimensionValue;
use Google\Analytics\Data\V1beta\MetricValue;
use Google\Analytics\Data\V1beta\Row;
use Google\Analytics\Data\V1beta\RunReportResponse;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class GoogleAnalyticsServiceTest extends TestCase
{
    public function test_dashboard_maps_reports_and_period_comparisons(): void
    {
        config()->set('services.google_analytics.property_id', '551482850');
        Cache::forget('google-analytics.dashboard.551482850');

        $responses = [
            $this->response([], ['100', '200', '300', '0.5']),
            $this->response([], ['50', '100', '150', '0.25']),
            $this->response([['20260824']], ['20']),
            $this->response([['mobile']], ['60']),
            $this->response([['google']], ['80']),
            $this->response([['/']], ['120', '0.75']),
            $this->response([['page_view']], ['200', '90']),
        ];
        $dashboard = (new GoogleAnalyticsService(function () use (&$responses): RunReportResponse {
            return array_shift($responses);
        }))->dashboard();

        $this->assertSame('ok', $dashboard['status']);
        $this->assertSame(100.0, $dashboard['metrics']['users']['value']);
        $this->assertSame(100.0, $dashboard['metrics']['users']['change']);
        $this->assertSame([20.0], $dashboard['traffic'][0]['metrics']);
        $this->assertSame(['mobile'], $dashboard['devices'][0]['dimensions']);
        $this->assertSame([120.0, 0.75], $dashboard['pages'][0]['metrics']);
    }

    public function test_dashboard_returns_safe_unavailable_state_on_failure(): void
    {
        config()->set('services.google_analytics.property_id', '551482850');
        Cache::forget('google-analytics.dashboard.551482850');

        $dashboard = (new GoogleAnalyticsService(fn (): RunReportResponse => throw new \RuntimeException('offline')))->dashboard();

        $this->assertSame('unavailable', $dashboard['status']);
        $this->assertSame('Google Analytics data is temporarily unavailable.', $dashboard['error']);
    }

    /**
     * @param  list<list<string>>  $dimensions
     * @param  list<string>  $metrics
     */
    private function response(array $dimensions, array $metrics): RunReportResponse
    {
        return new RunReportResponse([
            'rows' => [new Row([
                'dimension_values' => array_map(fn (string $value): DimensionValue => new DimensionValue(['value' => $value]), $dimensions[0] ?? []),
                'metric_values' => array_map(fn (string $value): MetricValue => new MetricValue(['value' => $value]), $metrics),
            ])],
        ]);
    }
}
