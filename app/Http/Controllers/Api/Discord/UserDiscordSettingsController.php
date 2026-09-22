<?php

namespace App\Http\Controllers\Api\Discord;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use NotificationChannels\Discord\Discord;

class UserDiscordSettingsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'discord_user_id' => 'required|string|max:255',
        ]);

        $userId = $request->input('discord_user_id');
        $channelId = app(Discord::class)->getPrivateChannel($userId);

        Auth::user()->update([
            'discord_user_id' => $userId,
            'discord_private_channel_id' => $channelId,
        ]);

        return redirect()->route('profile')->with('success', __('Discord account linked!'));
    }
}
