<?php

namespace App\Services;

use App\Mail\SendTelegramValidationCodeMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use NotificationChannels\Telegram\TelegramMessage;
use NotificationChannels\Telegram\TelegramUpdates;

class TelegramChatSetupService
{
    public static function start($chatId){
        $msg = TelegramMessage::create()
            ->to($chatId);

            foreach (trans('telegram.start', ['app_name' => config('app.name')]) ?? [] as $line) {
                $msg->line($line);
            }

            $msg->send();
    }

    public static function setupChatId($chatId, $email) {
        $user = User::where('email', $email)->first();
        if($user) {
            $code = random_int(1000, 99999);
            $user->update([
                'telegram_chat_id' => $chatId,
                'telegram_validation_code' => $code
            ]);

            \Illuminate\Support\defer(function () use ($user, $code) {
                Mail::to($user->email)->send(new SendTelegramValidationCodeMail($user, $code));
            });

            $msg = TelegramMessage::create()
                ->to($chatId);

            foreach (trans('telegram.link') ?? [] as $line) {
                $msg->line($line);
            }

            $msg->send();
        } else {
            $error = TelegramMessage::create()
                ->to($chatId);

            foreach (trans('telegram.errors.link.no_user', ['email' => $email]) ?? [] as $line) {
                $error->line($line);
            }

            $error->send();
        }
    }

    public static function validateLinking($chatId, $code) {
        $user = User::where('telegram_chat_id', $chatId)->where('telegram_validation_code', $code)->first();

        if($user) {
            $user->update([
                'telegram_validated' => true,
                'telegram_validation_code' => null
            ]);

            $msg = TelegramMessage::create()
                ->to($chatId);

            foreach (trans('telegram.code', ['app_name' => config('app.name')]) ?? [] as $line) {
                $msg->line($line);
            }

            $msg->send();
        } else {
            $error = TelegramMessage::create()
                ->to($chatId);

            foreach (trans('telegram.errors.code.no_user') ?? [] as $line) {
                $error->line($line);
            }

            $error->send();
        }
    }

    public static function stop($chatId) {
        $user = User::where('telegram_chat_id', $chatId)->first();

        if($user) {
            $user->update([
                'telegram_chat_id' => null,
            ]);

            $msg = TelegramMessage::create()
                ->to($chatId);

            foreach (trans('telegram.stop') ?? [] as $line) {
                $msg->line($line);
            }

            $msg->send();
        }
    }
}
