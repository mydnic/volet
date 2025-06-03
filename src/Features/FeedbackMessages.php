<?php

namespace Mydnic\Volet\Features;

class FeedbackMessages extends BaseFeature
{
    protected array $categories = [];

    public function __construct()
    {
        $this->setLabel('Send us feedback');
        $this->setIcon('https://api.iconify.design/lucide:message-square.svg?color=%23888888');
    }

    public function getId(): string
    {
        return 'feedback-messages';
    }

    public function getComponentName(): ?string
    {
        return 'VoletFeedbackMessages';
    }

    public function getScripts(): ?string
    {
        return null;
    }

    public function addCategory(string $slug, string $name, string $icon): static
    {
        $this->categories[] = [
            'slug' => $slug,
            'name' => $name,
            'icon' => $icon,
        ];

        return $this;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function getConfig(): array
    {
        return [
            'categories' => $this->categories,
            'routes' => [
                'store' => route('volet.feedback-messages.store'),
            ],
            'labels' => trans('volet::volet.feedback-messages'),
            'content' => config('volet.feedback-messages.content'),
            'csrfToken' => csrf_token(),
        ];
    }
}
