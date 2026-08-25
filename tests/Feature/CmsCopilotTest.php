<?php

use App\Models\User;
use App\Services\Cms\CopilotAgentFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Laravel\Ai\Responses\Data\Meta;
use Laravel\Ai\Responses\Data\Usage;
use Laravel\Ai\Responses\StructuredAgentResponse;

uses(RefreshDatabase::class);

function generatePayload(array $overrides = []): array
{
    return [
        'context' => 'brand-introduction',
        'target' => 'section',
        'target_action' => '/cms/homepage/brand-introduction',
        'prompt' => 'Create warm homepage copy.',
        'current_values' => ['title' => 'Current title'],
        ...$overrides,
    ];
}

test('guests cannot use cms copilot', function () {
    $this->postJson('/cms/copilot/generate', generatePayload())->assertUnauthorized();
});

test('copilot returns only allowed fields for a form', function () {
    $structured = [
        'reply' => 'Suggestions ready.', 'sources' => [],
        'suggestions' => [
            ['field' => 'title', 'label' => 'Headline', 'value' => 'Quiet Luxury in Canggu'],
            ['field' => 'image_1', 'label' => 'Image', 'value' => 'not-allowed'],
            ['field' => 'unknown', 'label' => 'Unknown', 'value' => 'ignored'],
            ['field' => 'paragraph_1', 'label' => 'Paragraph', 'value' => str_repeat('A', 10001)],
            ['field' => 'quote', 'label' => 'Quote', 'value' => 'A private retreat by the sea'],
        ],
    ];
    $response = new StructuredAgentResponse('copilot-test', $structured, json_encode($structured), new Usage, new Meta('deepseek', 'deepseek-v4-flash'));
    config([
        'services.copilot.text_provider' => 'deepseek',
        'services.copilot.text_model' => 'deepseek-v4-flash',
    ]);
    $agent = new class($response)
    {
        public array $arguments = [];

        public function __construct(private object $response) {}

        public function prompt(...$arguments): object
        {
            $this->arguments = $arguments;

            return $this->response;
        }
    };
    $factory = Mockery::mock(CopilotAgentFactory::class);
    $factory->shouldReceive('make')->once()->andReturn($agent);
    $this->instance(CopilotAgentFactory::class, $factory);
    $this->actingAs(User::factory()->create())->postJson('/cms/copilot/generate', generatePayload())
        ->assertOk()
        ->assertJsonPath('reply', 'Suggestions ready.')
        ->assertJsonCount(2, 'suggestions')
        ->assertJsonPath('suggestions.0.field', 'title')
        ->assertJsonPath('suggestions.1.field', 'quote');
    expect($agent->arguments[2])->toBe('deepseek')
        ->and($agent->arguments[3])->toBe('deepseek-v4-flash');
});

test('copilot rejects excluded or unknown form targets', function () {
    $user = User::factory()->create();
    foreach ([['context' => 'footer'], ['context' => 'navbar'], ['context' => 'featured-in'], ['target_action' => '/cms/homepage/navbar']] as $override) {
        $this->actingAs($user)->postJson('/cms/copilot/generate', generatePayload($override))->assertUnprocessable();
    }
});

test('copilot blocks local website urls without calling providers', function () {
    Http::fake();
    $this->actingAs(User::factory()->create())->postJson('/cms/copilot/generate', generatePayload([
        'prompt' => 'Use https://127.0.0.1/about',
    ]))->assertUnprocessable()->assertJsonValidationErrors('prompt');
    Http::assertNothingSent();
});
