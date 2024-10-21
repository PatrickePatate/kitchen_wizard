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
                    $suggestion = TelegramMessage::create()
                        ->to($user->telegram_chat_id);

                    foreach (trans('telegram.suggestions', [
                        'name'=> $user->name,
                        'starter' => $selection->starter()->title,
                        'main' => $selection->main()->title,
                        'dessert' => $selection->dessert()->title,
                    ]) as $line) {
                        $suggestion->line($line);
                    }

                    $suggestion
                        ->button(__('Voir les recettes'), route('home'))
                        ->button(__('Voir le plat'), route('recipe', $selection->main()))
                        ->button(__("Voir l'entrée"), route('recipe', $selection->starter()))
                        ->button(__('Voir le dessert'), route('recipe', $selection->dessert()))
                        ->send();
                }
                if($user->isEmailNotificationsActive()) {
                    Mail::to($user)->send(new RecipesSuggestionsMail($user));
                }

            } catch(\Exception $e) {
                report($e);
            }

        });
    }
}
