<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Mydnic\Volet\Features\FeatureManager;
use Mydnic\Volet\Features\FeedbackMessages;

class VoletServiceProvider extends ServiceProvider
{
    public function boot(FeatureManager $volet): void
    {
        // Register and configure the Feedback Messages feature
        $this->registerFeedbackMessagesFeature($volet);

        // Example of registering a custom feature
        // $volet->register(new YourCustomFeature());
    }

    private function registerFeedbackMessagesFeature(FeatureManager $volet): void
    {
        $volet->register(
            (new FeedbackMessages)
                // Configure feature display
                ->setLabel('Send us feedback')
                ->setIcon('https://api.iconify.design/lucide:message-square.svg?color=%23888888')

                // Add feedback categories
                ->addCategory(
                    slug: 'bug',
                    name: 'Bug Report',
                    icon: 'https://api.iconify.design/lucide:bug.svg?color=%23888888'
                )
                ->addCategory(
                    slug: 'improvement',
                    name: 'Improvement',
                    icon: 'https://api.iconify.design/lucide:lightbulb.svg?color=%23888888'
                )
                ->addCategory(
                    slug: 'general',
                    name: 'General Feedback',
                    icon: 'https://api.iconify.design/lucide:smile.svg?color=%23888888'
                )
        );
    }
}
