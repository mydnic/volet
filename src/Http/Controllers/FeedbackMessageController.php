<?php

namespace Mydnic\Volet\Http\Controllers;

use Illuminate\Http\Request;
use Mydnic\Volet\Features\FeatureManager;

class FeedbackMessageController
{
    public function store(Request $request)
    {
        $model = config('volet.feedback-messages.model');
        $categories = collect(
            app(FeatureManager::class)
                ->getFeature('feedback-messages')
                ->getCategories()
        )
            ->pluck('slug')
            ->toArray();

        $validated = $request->validate([
            'message' => 'required|string|max:255',
            'category' => 'required|string|in:'.implode(',', $categories),
            'user_info' => 'nullable|array',
        ]);

        $feedback = $model::create([
            'message' => $validated['message'],
            'category' => $validated['category'],
            'user_info' => $this->getUserInfo($request),
        ]);

        return response()->json($feedback);
    }

    protected function getUserInfo(Request $request)
    {
        return array_merge([
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->header('referer'),
            'language' => $request->getPreferredLanguage(),
            'user_id' => auth()->check() ? auth()->id() : null,
        ], $request->user_info ?? []);
    }
}
