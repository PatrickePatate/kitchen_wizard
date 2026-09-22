<?php

namespace Tests\Unit\Models;

use App\Models\Recipe;
use App\Models\RecipeDailySelection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_initials_are_built_from_the_first_letter_of_each_name_part(): void
    {
        $user = User::factory()->create(['name' => 'Jean Dupont']);

        $this->assertSame('JD', $user->initials);
    }

    public function test_avatar_is_a_gravatar_url_hashed_from_the_lowercase_trimmed_email(): void
    {
        $user = User::factory()->create(['email' => ' Test@Example.com ']);

        $expected = 'https://www.gravatar.com/avatar/'.hash('sha256', 'test@example.com').'?s=250&d=identicon';

        $this->assertSame($expected, $user->avatar);
    }

    public function test_it_is_not_telegram_setup_by_default(): void
    {
        $user = User::factory()->create();

        $this->assertFalse($user->isTelegramAccountSetup());
    }

    public function test_it_is_telegram_setup_once_chat_id_and_validation_are_present(): void
    {
        $user = User::factory()->create([
            'telegram_chat_id' => '12345',
            'telegram_validated' => true,
        ]);

        $this->assertTrue($user->isTelegramAccountSetup());
    }

    public function test_email_notifications_active_reflects_the_flag(): void
    {
        $user = User::factory()->create(['is_email_notifications_active' => true]);
        $this->assertTrue($user->isEmailNotificationsActive());

        $user = User::factory()->create(['is_email_notifications_active' => false]);
        $this->assertFalse($user->isEmailNotificationsActive());
    }

    public function test_has_at_least_one_notification_channel_active_is_true_when_none_are_active(): void
    {
        $user = User::factory()->create([
            'is_email_notifications_active' => false,
            'telegram_chat_id' => null,
            'telegram_validated' => false,
        ]);

        // Note: the method name is misleading, it actually returns true when NO channel is active.
        $this->assertTrue($user->hasAtLeastOneNotificationChannelActive());
    }

    public function test_has_at_least_one_notification_channel_active_is_false_when_email_is_active(): void
    {
        $user = User::factory()->create(['is_email_notifications_active' => true]);

        $this->assertFalse($user->hasAtLeastOneNotificationChannelActive());
    }

    public function test_route_notification_for_discord_prefers_the_private_channel_id(): void
    {
        $user = User::factory()->create([
            'discord_private_channel_id' => 'channel-1',
            'discord_user_id' => 'user-1',
        ]);

        $this->assertSame('channel-1', $user->routeNotificationForDiscord());
    }

    public function test_route_notification_for_discord_falls_back_to_the_user_id(): void
    {
        $user = User::factory()->create([
            'discord_private_channel_id' => null,
            'discord_user_id' => 'user-1',
        ]);

        $this->assertSame('user-1', $user->routeNotificationForDiscord());
    }

    public function test_deleting_a_user_cascades_daily_selections_and_liked_recipes(): void
    {
        $user = User::factory()->create();
        RecipeDailySelection::factory()->create(['user_id' => $user->id]);
        $recipe = Recipe::factory()->create();
        $user->likedRecipes()->create(['recipe_id' => $recipe->id, 'liked_at' => now()]);

        $user->delete();

        $this->assertDatabaseCount('recipe_daily_selections', 0);
        $this->assertDatabaseCount('recipe_likes', 0);
    }
}
