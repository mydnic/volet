<?php

namespace Mydnic\Volet\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it can get feature settings', function () {
    $response = $this->getJson('/volet/settings');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'features' => [
                '*' => [
                    'id',
                    'label',
                    'icon',
                    'component',
                ],
            ],
        ]);
});
