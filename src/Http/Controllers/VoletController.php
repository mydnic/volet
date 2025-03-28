<?php

namespace Mydnic\Volet\Http\Controllers;

use Mydnic\Volet\Features\FeatureManager;

class VoletController
{
    public function __construct(
        protected FeatureManager $features
    ) {}

    public function settings()
    {
        return response()->json([
            'features' => $this->features
                ->getEnabledFeatures()
                ->map(fn ($feature) => [
                    'id' => $feature->getId(),
                    'label' => $feature->getLabel(),
                    'icon' => $feature->getIcon(),
                    'component' => $feature->getVueComponent(),
                    'config' => $feature->getConfig(),
                ])
                ->values()
                ->toArray(),
        ]);
    }
}
