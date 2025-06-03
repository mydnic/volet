<?php

return [
    // The icon that will be shown in the button bubble. Can be a SVG or an image URL
    'icon' => 'https://api.iconify.design/heroicons:chat-bubble-left-ellipsis.svg?color=%23FFFFFF',

    /*
    |--------------------------------------------------------------------------
    | Features Configuration
    |--------------------------------------------------------------------------
    |
    | Configure settings for individual features.
    |
    */
    'feedback-messages' => [
        'table' => 'volet_feedback_messages',

        // The controller class to use for feedback endpoints
        'controller' => \Mydnic\Volet\Http\Controllers\FeedbackMessageController::class,

        // The model class to use for feedback messages
        'model' => \Mydnic\Volet\Models\FeedbackMessage::class,

        'routes' => [
            // The URI prefix for feedback message routes
            'prefix' => 'feedback',

            // The middleware to apply to feedback message routes
            'middleware' => [
                'web',
                // Add your custom middleware here
                // 'auth',
                // 'verified',
            ],
        ],

        'content' => [
            'success-icon' => 'https://api.iconify.design/heroicons:check-circle.svg?color=%2322c55e',
        ],
    ],
];
