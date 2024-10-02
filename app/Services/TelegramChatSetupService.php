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
        TelegramMessage::create()
            ->to($chatId)
            ->line('👋 Bienvenue sur ' . config('app.name') . '!')
            ->line('')
            ->line('🔗 Pour lier votre compte, tapez /link `<VOTRE EMAIL>`')
            ->line('')
            ->line('📝 Exemple: /link arthur@entreprise.fr')
            ->send();
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

            TelegramMessage::create()
                ->to($chatId)
                ->line('🚀 Hey ! Bonne nouvelle !')
                ->line('')
                ->line("C'est la dernière ligne droite ! 🏁")
                ->line('')
                ->line('Vérifie tes mails, tu as reçu un code de validation pour valider la laison de ton compte !')
                ->send();
        } else {
            TelegramMessage::create()
                ->to($chatId)
                ->line("⚠️ Oups ! Une erreur s'est produite !")
                ->line('')
                ->line('🔍 Aucun compte n\'a été trouvé avec l\'email ' . (empty($email) ? '*vide*' : $email))
                ->send();
        }
    }

    public static function validateLinking($chatId, $code) {
        $user = User::where('telegram_chat_id', $chatId)->where('telegram_validation_code', $code)->first();

        if($user) {
            $user->update([
                'telegram_validated' => true,
                'telegram_validation_code' => null
            ]);

            TelegramMessage::create()
                ->to($chatId)
                ->line('🚀 Hey ! Bonne nouvelle !')
                ->line('')
                ->line('🎉 Votre compte a été lié à ' . config('app.name').' 🎉')
                ->line('')
                ->line('Vous recevrez désormais des suggestions de recettes les matins via Telegram !')
                ->send();
        } else {
            TelegramMessage::create()
                ->to($chatId)
                ->line("⚠️ Oups ! Une erreur s'est produite !")
                ->line('')
                ->line('🔍 Le code de validation est incorrect ou a expiré !')
                ->send();
        }

    }
}
