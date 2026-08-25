<?php

namespace App\Services\Cms;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\StructuredAnonymousAgent;

class CopilotAgentFactory
{
    /**
     * @param  iterable<object>  $messages
     */
    public function make(string $instructions, iterable $messages, \Closure $schema): object
    {
        return new StructuredAnonymousAgent(
            instructions: $instructions,
            messages: $messages,
            tools: [],
            schema: fn (JsonSchema $jsonSchema): array => $schema($jsonSchema),
        );
    }
}
