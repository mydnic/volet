<?php

use Illuminate\Support\Facades\Route;
use Mydnic\Volet\Http\Controllers\VoletController;

Route::prefix('volet')->group(function () {
    Route::get('settings', [VoletController::class, 'settings'])
        ->middleware('web');

    Route::prefix(config('volet.feedback-messages.routes.prefix'))
        ->middleware(config('volet.feedback-messages.routes.middleware'))
        ->group(function () {
            Route::post('/', [config('volet.feedback-messages.controller'), 'store'])
                ->name('volet.feedback-messages.store');
        });
});
