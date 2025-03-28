<?php

namespace Mydnic\Volet\Features;

use Illuminate\Support\Collection;

class FeatureManager
{
    protected Collection $features;

    public function __construct()
    {
        $this->features = collect();
    }

    public function register(Feature $feature): static
    {
        $this->features->put($feature->getId(), $feature);

        return $this;
    }

    public function getFeature(string $id): ?Feature
    {
        return $this->features->get($id);
    }

    public function getFeatures(): Collection
    {
        return $this->features;
    }

    public function getEnabledFeatures(): Collection
    {
        return $this->features->filter(fn (Feature $feature) => $feature->isEnabled());
    }

    public function getFeatureConfigs(): array
    {
        return $this->getEnabledFeatures()
            ->mapWithKeys(fn (Feature $feature) => [
                $feature->getId() => $feature->getConfig(),
            ])
            ->toArray();
    }
}
