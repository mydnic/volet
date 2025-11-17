<?php

namespace Mydnic\Volet\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Mydnic\Volet\Models\FeedbackMessage;

class NewFeedbackMessageNotification extends Notification
{
    public function __construct(public FeedbackMessage $feedbackMessage) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('[Volet] New feedback message')
            ->line('Category: '.$this->feedbackMessage->category)
            ->line('Message: '.$this->feedbackMessage->message);
    }
}
