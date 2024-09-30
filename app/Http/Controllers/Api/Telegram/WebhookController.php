<?php

namespace App\Http\Controllers\Api\Telegram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;

class WebhookController extends Controller
{
    public function update(Request $request)
    {
        Log::info('Telegram webhook called', $request->all());
    }
}
