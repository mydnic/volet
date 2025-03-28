<?php

namespace Mydnic\Volet\Tests\Unit;

use Mydnic\Volet\Models\FeedbackMessage;

test('it can create feedback messages', function () {
    $message = new FeedbackMessage;
    $message->fill([
        'message' => 'Test message',
        'category' => 'bug',
        'user_info' => ['url' => 'http://test.com'],
    ]);

    expect($message->message)->toBe('Test message');
    expect($message->category)->toBe('bug');
    expect($message->user_info)->toBeArray();
    expect($message->user_info['url'])->toBe('http://test.com');
});

test('it has correct default status', function () {
    $message = new FeedbackMessage;
    $message->status = 'new';
    expect($message->status)->toBe('new');
});

test('it casts user_info to array', function () {
    $message = new FeedbackMessage;
    $message->user_info = ['browser' => 'test'];
    expect($message->user_info)->toBeArray();
    expect($message->user_info['browser'])->toBe('test');
});
