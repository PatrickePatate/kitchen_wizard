<?php

namespace App\Notifications;

use App\Models\Recipe;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramFile;
use NotificationChannels\Telegram\TelegramMessage;

class MealSuggestionTelegramNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Recipe $main, public Recipe $starter, public Recipe $dessert)
    {
        //
    }


    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['telegram'];
    }

   public function toTelegram(object $notifiable): TelegramMessage
    {
        return TelegramMessage::create()
            ->to($notifiable->telegram_chat_id ?? config('services.telegram-bot-api.user-id'))
            ->line(__("Hey ! J'ai une suggestion de recette pour toi !"))
            ->line('')
            ->line(__('🍽️ *En plat :*'))
            ->line("**".$this->main->title."**")
            ->line($this->main->url)
            ->line('')
            ->line(__('🥗 *En entrée :*'))
            ->line("**".$this->starter->title."**")
            ->line('')
            ->line(__('🍰 *En dessert :*'))
            ->line("**".$this->dessert->title."**")
            ->line('')
            ->button(__("Dévouvrir le plat"), $this->main->url)
            ->button(__("Dévouvrir l'entrée"), $this->starter->url)
            ->button(__("Dévouvrir le dessert"), $this->dessert->url)
            ->button(__('Me proposer d\'autres recettes'), route('home'));
    }

}
