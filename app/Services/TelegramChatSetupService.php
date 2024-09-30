<?php

namespace App\Services;

use App\Models\User;
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
            //TODO: send a code by mail before linking the account
            $user->telegram_chat_id = $chatId;
            $user->save();

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
                ->line('🔍 Aucun compte n\'a été trouvé avec l\'email ' . (empty($email) ? '*vide*' : $email))
                ->send();
        }
    }

    public function handle()
    {
        $updates = TelegramUpdates::create()
            // (Optional). Get's the latest update. NOTE: All previous updates will be forgotten using this method.
            // ->latest()

            // (Optional). Limit to 2 updates (By default, updates starting with the earliest unconfirmed update are returned).
            ->limit(5)

            // (Optional). Add more params to the request.
            ->options([
                'timeout' => 0,
            ])
            ->get();

        if($updates['ok']) {
            foreach ($updates['result'] as $update) {
                // Update ID
                $updateId = $update['update_id'];

                // Message
                $message = $update['message']['text'];

                // Chat ID
                $chatId = $update['message']['chat']['id'];

                if($message === '/start') {
                    // Send a message to the chat
                    try{
                        TelegramMessage::create()
                            ->to($chatId)
                            ->content('Liez votre compte Telegram à ' . config('app.name'))
                            ->line("")
                            ->line("Pour lier votre compte, tapez /link __<email>__")
                            ->send();
                    } catch (\Exception $e) {
                        // Handle exception
                    }
                } elseif(str_starts_with($message, '/link')) {
                    // Send a message to the chat
                    try{
                        $user = User::where('email', trim(substr($message, 6)))->first();
                        if($user) {
                            $user->telegram_chat_id = $chatId;
                            $user->save();

                            TelegramMessage::create()
                                ->to($chatId)
                                ->content("Merci ! Votre compte a été lié à " . config('app.name'))
                                ->send();
                        } else {
                            TelegramMessage::create()
                                ->to($chatId)
                                ->content("Aucun compte n'a été trouvé avec l'email " . trim(substr($message, 6)))
                                ->send();
                        }
                    } catch (\Exception $e) {
                        // Handle exception
                    }
                }
            }

        }
    }
}
