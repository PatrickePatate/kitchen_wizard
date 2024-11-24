<?php

namespace App\Http\Controllers\Api\Telegram;

use App\Http\Controllers\Controller;
use App\Services\TelegramChatSetupService;
use Illuminate\Http\Request;
use Log;
use NotificationChannels\Telegram\TelegramMessage;

class WebhookController extends Controller
{
    public function update(Request $request)
    {
        if($request->has('update_id')){
            $chatId = $request->message['chat']['id'] ?? null;
            $message = trim($request->message['text'] ?? '');

            if($message === '/start'){
                TelegramChatSetupService::start($chatId);
                return;
            }

            if(str_starts_with($message, '/link')){
                $email = trim(substr($message, 6));

                if(empty($email)){
                    $error = TelegramMessage::create()
                        ->to($chatId);

                    foreach (trans('telegram.errors.link.no_email') ?? [] as $line) {
                        $error->line($line);
                    }

                    $error->send();
                    return;
                }

                TelegramChatSetupService::setupChatId($chatId, $email);
                return;
            }

            if(str_starts_with($message, '/code')){
                $code = trim(substr($message, 6));

                if(empty($code)){
                    $error = TelegramMessage::create()
                        ->to($chatId);

                    foreach (trans('telegram.errors.link.no_code') ?? [] as $line) {
                        $error->line($line);
                    }

                    $error->send();
                    return;
                }

                TelegramChatSetupService::validateLinking($chatId, $code);
                return;
            }
        } else{
            Log::error('Invalid webhook received from telegram', $request->all());
        }
    }
}
