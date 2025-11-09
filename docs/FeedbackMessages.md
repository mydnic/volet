# Feedback Messages

This feature is already available and installed, it comes out of the box with Volet's core.

The feedback messages feature allows users to submit feedback in different categories. Configure the table name in your `config/volet.php`:

```php
return [
    'feedback-messages' => [
        'table' => 'custom_feedback_table', // Default: 'volet_feedback_messages'
        
        // ...
    ],
];
```

Configure the feature in your `VoletServiceProvider`:

```php
use Mydnic\Volet\Features\FeedbackMessages;

$volet->register(
    (new FeedbackMessages())
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
);
```

You can enable/disable the feature:
```php
(new Mydnic\Volet\Features\FeedbackMessages())->disable();

(new Mydnic\Volet\Features\FeedbackMessages())->enable();
```

## Notifications

A simple mail notification is already at your disposal, you just need to setup your email addresses in `config/volet.php`

```php
return [
    'feedback-messages' => [
        // ...
        
         'mail_notification' => [
            'enabled' => true,
            'send_mails_to' => [
                // List of emails to send the notification to
                // 'admin@example.com',
            ],
        ]
    ],
];
```

### Custom Notification 

You can easily use Laravel model's events to send notifications when a new feedback message is submitted:

```php
namespace App\Observers;

use Mydnic\Volet\Models\FeedbackMessage;
use App\Notifications\NewFeedbackMessageNotification;
use Illuminate\Support\Facades\Notification;

class FeedbackMessageObserver
{
    public function created(FeedbackMessage $feedbackMessage)
    {
        // Send notification to administrators
        Notification::route('mail', 'admin@example.com')
            ->notify(new NewFeedbackMessageNotification($feedbackMessage));
    }
}
```

```php
// Register the observer in your AppServiceProvider
public function boot()
{
    FeedbackMessage::observe(FeedbackMessageObserver::class);
}
```

## Filament Admin Panel

I've made a filament plugin to easily manage the feedback messages. You can find it here: [mydnic/volet-feedback-messages-filament-plugin)](https://github.com/mydnic/volet-feedback-messages-filament-plugin).
