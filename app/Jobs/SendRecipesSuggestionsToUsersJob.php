<?php

namespace App\Jobs;

use App\Mail\RecipesSuggestionsMail;
use App\Models\RecipeDailySelection;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use NotificationChannels\Telegram\TelegramMessage;

class SendRecipesSuggestionsToUsersJob implements ShouldQueue
{
    use Queueable;


    public function handle(): void
    {
        User::all()->each(function (User $user) {
            try{
                $selection = RecipeDailySelection::forUser($user);
                if($user->isTelegramAccountSetup()) {
                    TelegramMessage::create()
                        ->to($user->telegram_chat_id)
                        ->line('☀️ Bonjour ' . $user->name . ' !')
                        ->line('Voici une suggestion de recette pour vous aujourd\'hui !')
                        ->line('')
                        ->line('🥗 Entrée: ' . $selection->starter()->title)
                        ->line('')
                        ->line('🍲 Plat: ' . $selection->main()->title)
                        ->line('')
                        ->line('🍰 Dessert: ' . $selection->dessert()->title)
                        ->button('Voir les recettes', route('home'))
                        ->button('Voir le plat', route('recipe', $selection->main()))
                        ->button('Voir l\'entrée', route('recipe', $selection->starter()))
                        ->button('Voir le dessert', route('recipe', $selection->dessert()))
                        ->send();
                }
                if($user->isEmailNotificationsActive()) {
                    Mail::to($user)->send(new RecipesSuggestionsMail($user));
                }
                throw new Exception('test');
            } catch(\Exception $e) {
                report($e);
            }

        });
    }
}
