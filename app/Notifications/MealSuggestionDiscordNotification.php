<?php

namespace App\Notifications;

use App\Models\Recipe;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Discord\DiscordChannel;
use NotificationChannels\Discord\DiscordMessage;

class MealSuggestionDiscordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Recipe $main, public Recipe $starter, public Recipe $dessert)
    {
        //
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [DiscordChannel::class];
    }

    public function toDiscord(object $notifiable): DiscordMessage
    {
        return DiscordMessage::create(implode("\n", [
            __("Hey ! J'ai une suggestion de recette pour toi !"),
            '',
            __('🥗 *En entrée :*').' **'.$this->starter->title.'**',
            $this->starter->url,
            '',
            __('🍽️ *En plat :*').' **'.$this->main->title.'**',
            $this->main->url,
            '',
            __('🍰 *En dessert :*').' **'.$this->dessert->title.'**',
            $this->dessert->url,
        ]));
    }
}
