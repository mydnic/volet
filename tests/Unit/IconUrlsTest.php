<?php

namespace Mydnic\Volet\Tests\Unit;

use Mydnic\Volet\Features\FeedbackMessages;

// Iconify only serves "<set>/<icon>.svg"; the "<set>:<icon>.svg" form returns 404.
$iconifyPath = '#^https://api\.iconify\.design/[a-z0-9-]+/[a-z0-9-]+\.svg\?#';

test('default config icons use the iconify path form', function () use ($iconifyPath) {
    expect(config('volet.icon'))->toMatch($iconifyPath)
        ->and(config('volet.close-icon'))->toMatch($iconifyPath)
        ->and(config('volet.feedback-messages.content.success-icon'))->toMatch($iconifyPath);
});

test('feedback messages feature default icon uses the iconify path form', function () use ($iconifyPath) {
    expect((new FeedbackMessages)->getIcon())->toMatch($iconifyPath);
});
