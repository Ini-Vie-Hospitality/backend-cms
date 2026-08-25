<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_redirects_to_the_configured_frontend_url(): void
    {
        config(['services.homepage.frontend_url' => 'https://frontend.example.test']);

        $this->get('/')->assertRedirect('https://frontend.example.test');
    }
}
