<?php

use App\Http\Controllers\Api\Telegram\WebhookController;

Route::post('/telegram/callback', [WebhookController::class, 'update'])->name('webhook.telegram');
