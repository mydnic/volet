<?php

namespace Mydnic\Volet\Tests\Unit;

use Mydnic\Volet\Features\Feature;
use Mydnic\Volet\Features\FeatureManager;
use Mydnic\Volet\Features\FeedbackMessages;

class TestFeature implements Feature
{
    protected bool $enabled = true;

    protected string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return 'Test Feature';
    }

    public function setLabel(string $label): static
    {
        return $this;
    }

    public function getIcon(): ?string
    {
        return null;
    }

    public function setIcon(string $icon): static
    {
        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function enable(): static
    {
        $this->enabled = true;

        return $this;
    }

    public function disable(): static
    {
        $this->enabled = false;

        return $this;
    }

    public function getConfig(): array
    {
        return [];
    }

    public function getVueComponent(): ?string
    {
        return null;
    }
}

test('it can register and retrieve features', function () {
    $manager = new FeatureManager;
    $feature = new FeedbackMessages;

    $manager->register($feature);

    expect($manager->getFeature('feedback-messages'))->toBe($feature);
    expect($manager->getFeatures()->count())->toBe(1);
});

test('it can get enabled features', function () {
    $manager = new FeatureManager;

    $enabledFeature = new TestFeature('feature-1');
    $enabledFeature->enable();

    $disabledFeature = new TestFeature('feature-2');
    $disabledFeature->disable();

    $manager->register($enabledFeature);
    $manager->register($disabledFeature);

    expect($enabledFeature->isEnabled())->toBeTrue();
    expect($disabledFeature->isEnabled())->toBeFalse();
    expect($manager->getEnabledFeatures()->count())->toBe(1);
    expect($manager->getEnabledFeatures()->first())->toBe($enabledFeature);
});

test('it returns null for non-existent features', function () {
    $manager = new FeatureManager;
    expect($manager->getFeature('non-existent'))->toBeNull();
});
