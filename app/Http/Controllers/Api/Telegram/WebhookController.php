<?php

namespace App\Http\Controllers\Api\Telegram;

use App\Http\Controllers\Controller;
use App\Services\TelegramChatSetupService;
use Illuminate\Http\Request;
use Log;

class WebhookController extends Controller
{
    public function update(Request $request)
    {
        if($request->has('update_id')){
            $chatId = $request->message['chat']['id'] ?? null;
            $message = trim($request->message['text'] ?? '');

            if($message === '/start'){
                // Send a message to the chat
                TelegramChatSetupService::start($chatId);
                return;
            }

            if(str_starts_with($message, '/link')){
                // Send a message to the chat
                $email = trim(substr($message, 6));
                TelegramChatSetupService::setupChatId($chatId, $email);
                return;
            }
        } else{
            Log::error('Invalid webhook received from telegram', $request->all());
        }
    }
}
