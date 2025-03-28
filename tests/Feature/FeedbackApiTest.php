<?php

namespace Mydnic\Volet\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it can submit feedback through API', function () {
    $this->withoutExceptionHandling();

    $response = $this->postJson('/volet/feedback', [
        'category' => 'bug',
        'message' => 'Test feedback message',
        'user_info' => [
            'url' => 'http://test.com',
            'userAgent' => 'Test Browser',
        ],
    ]);

    $response->assertStatus(200);

    $this->assertDatabaseHas(config('volet.feedback-messages.table'), [
        'category' => 'bug',
        'message' => 'Test feedback message',
    ]);
});

test('it validates required fields', function () {
    $response = $this->postJson('/volet/feedback', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['category', 'message']);
});
