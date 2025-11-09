<?php

namespace Mydnic\Volet\Tests\Unit;

use Illuminate\Support\Facades\Notification;
use Mydnic\Volet\Models\FeedbackMessage;
use Mydnic\Volet\Notifications\NewFeedbackMessageNotification;

beforeEach(function () {
    Notification::fake();
});

test('it sends notification when feedback message is created and notification is enabled', function () {
    // Enable notification
    config([
        'volet.feedback-messages.mail_notification.enabled' => true,
        'volet.feedback-messages.mail_notification.send_mails_to' => ['admin@example.com'],
        'volet.feedback-messages.mail_notification.class' => NewFeedbackMessageNotification::class,
    ]);

    $feedbackMessage = FeedbackMessage::create([
        'message' => 'Test feedback message',
        'category' => 'bug',
        'user_info' => ['url' => 'http://test.com'],
    ]);

    Notification::assertSentOnDemand(
        NewFeedbackMessageNotification::class,
        function ($notification, $channels, $notifiable) use ($feedbackMessage) {
            return $notifiable->routes['mail'] === ['admin@example.com']
                && $notification->feedbackMessage->id === $feedbackMessage->id;
        }
    );
});

test('it does not send notification when notification is disabled', function () {
    // Disable notification
    config([
        'volet.feedback-messages.mail_notification.enabled' => false,
        'volet.feedback-messages.mail_notification.send_mails_to' => ['admin@example.com'],
    ]);

    FeedbackMessage::create([
        'message' => 'Test feedback message',
        'category' => 'bug',
        'user_info' => ['url' => 'http://test.com'],
    ]);

    Notification::assertNothingSent();
});

test('it does not send notification when no recipients are configured', function () {
    // Enable notification but no recipients
    config([
        'volet.feedback-messages.mail_notification.enabled' => true,
        'volet.feedback-messages.mail_notification.send_mails_to' => [],
    ]);

    FeedbackMessage::create([
        'message' => 'Test feedback message',
        'category' => 'bug',
        'user_info' => ['url' => 'http://test.com'],
    ]);

    Notification::assertNothingSent();
});

test('notification contains correct feedback message data', function () {
    $feedbackMessage = new FeedbackMessage([
        'message' => 'Test feedback message',
        'category' => 'bug',
    ]);

    $notification = new NewFeedbackMessageNotification($feedbackMessage);

    expect($notification->feedbackMessage->message)->toBe('Test feedback message');
    expect($notification->feedbackMessage->category)->toBe('bug');
});

test('notification uses mail channel', function () {
    $feedbackMessage = new FeedbackMessage([
        'message' => 'Test feedback message',
        'category' => 'bug',
    ]);

    $notification = new NewFeedbackMessageNotification($feedbackMessage);

    expect($notification->via(null))->toBe(['mail']);
});

test('notification mail message contains feedback details', function () {
    $feedbackMessage = new FeedbackMessage([
        'message' => 'Test feedback message',
        'category' => 'bug',
    ]);

    $notification = new NewFeedbackMessageNotification($feedbackMessage);
    $mailMessage = $notification->toMail(null);

    expect($mailMessage->introLines)->toContain('[Volet] New feedback message');
    expect($mailMessage->introLines)->toContain('Category: bug');
    expect($mailMessage->introLines)->toContain('Message: Test feedback message');
});

test('it sends notification to multiple recipients', function () {
    // Enable notification with multiple recipients
    config([
        'volet.feedback-messages.mail_notification.enabled' => true,
        'volet.feedback-messages.mail_notification.send_mails_to' => [
            'admin1@example.com',
            'admin2@example.com',
        ],
        'volet.feedback-messages.mail_notification.class' => NewFeedbackMessageNotification::class,
    ]);

    $feedbackMessage = FeedbackMessage::create([
        'message' => 'Test feedback message',
        'category' => 'bug',
        'user_info' => ['url' => 'http://test.com'],
    ]);

    Notification::assertSentOnDemand(
        NewFeedbackMessageNotification::class,
        function ($notification, $channels, $notifiable) use ($feedbackMessage) {
            return $notifiable->routes['mail'] === ['admin1@example.com', 'admin2@example.com']
                && $notification->feedbackMessage->id === $feedbackMessage->id;
        }
    );
});
