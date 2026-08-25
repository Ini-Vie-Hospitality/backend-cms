<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Analytics\GoogleAnalyticsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_users_receive_google_analytics_props(): void
    {
        $analytics = [
            'status' => 'ok',
            'period' => ['start' => '2026-07-27', 'end' => '2026-08-25'],
            'metrics' => [],
            'traffic' => [],
            'devices' => [],
            'sources' => [],
            'pages' => [],
            'events' => [],
            'error' => null,
        ];
        $service = Mockery::mock(GoogleAnalyticsService::class);
        $service->shouldReceive('dashboard')->once()->andReturn($analytics);
        $this->instance(GoogleAnalyticsService::class, $service);

        $response = $this->actingAs(User::factory()->create())->get(route('dashboard'));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('dashboard')
            ->where('analytics.status', 'ok')
            ->where('analytics.period.end', '2026-08-25'));
    }
}
