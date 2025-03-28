<?php

use Illuminate\Support\Facades\Route;

Route::prefix('volet')->group(function () {
    Route::get('settings', [\Mydnic\Volet\Http\Controllers\VoletController::class, 'settings']);

    Route::prefix(config('volet.feedback-messages.routes.prefix'))
        ->middleware(config('volet.feedback-messages.routes.middleware'))
        ->group(function () {
            Route::post('/', [config('volet.feedback-messages.controller'), 'store'])
                ->name('volet.api.store');
        });
});
