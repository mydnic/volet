<?php

namespace Mydnic\Volet\Tests\Unit;

use Mydnic\Volet\Features\FeedbackMessages;

test('it can configure feature display settings', function () {
    $feature = new FeedbackMessages;

    $feature
        ->setLabel('Test Label')
        ->setIcon('test-icon.svg');

    expect($feature->getLabel())->toBe('Test Label');
    expect($feature->getIcon())->toBe('test-icon.svg');
});

test('it can manage categories', function () {
    $feature = new FeedbackMessages;

    $feature->addCategory('bug', 'Bug Report', 'bug-icon.svg');

    $config = $feature->getConfig();
    expect($config['categories'])->toHaveCount(1);
    expect($config['categories'][0])->toMatchArray([
        'slug' => 'bug',
        'name' => 'Bug Report',
        'icon' => 'bug-icon.svg',
    ]);
});

test('it supports conditional configuration', function () {
    $feature = new FeedbackMessages;

    $feature->when(true, fn ($f) => $f->disable());
    expect($feature->isEnabled())->toBeFalse();

    $feature->when(false, fn ($f) => $f->enable());
    expect($feature->isEnabled())->toBeFalse(); // Should still be disabled

    $feature->when(true, fn ($f) => $f->enable());
    expect($feature->isEnabled())->toBeTrue();
});
