<?php

namespace Tests\Feature;

use App\DietEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class AccountControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_view_the_profile_page(): void
    {
        $response = $this->get(route('profile'));

        $response->assertRedirect(route('login'));
    }

    public function test_the_profile_page_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('profile'));

        $response->assertOk();
    }

    public function test_a_user_can_update_their_profile(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('profile'), [
            'name' => 'New Name',
            'email' => 'new@example.com',
            'preferred_diet' => DietEnum::VEGAN->value,
            'is_email_notifications_active' => '1',
        ]);

        $response->assertRedirect(route('profile'));
        $user->refresh();
        $this->assertSame('New Name', $user->name);
        $this->assertSame('new@example.com', $user->email);
        $this->assertSame(DietEnum::VEGAN, $user->preferred_diet);
        $this->assertTrue((bool) $user->is_email_notifications_active);
    }

    public function test_updating_the_profile_without_email_notifications_disables_them(): void
    {
        $user = User::factory()->create(['is_email_notifications_active' => true]);

        $this->actingAs($user)->post(route('profile'), [
            'name' => $user->name,
            'email' => $user->email,
        ]);

        $this->assertFalse((bool) $user->fresh()->is_email_notifications_active);
    }

    public function test_a_user_can_change_their_password(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('profile'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('new-password', $user->fresh()->password));
    }

    public function test_a_user_can_update_their_meteo_location(): void
    {
        $user = User::factory()->create();
        Cache::put('weather'.$user->id, ['stale' => true]);

        $response = $this->actingAs($user)->post(route('profile.store.meteo'), [
            'meteo_city' => 'Paris',
            'meteo_lat' => 48.8566,
            'meteo_lon' => 2.3522,
        ]);

        $response->assertRedirect(route('profile'));
        $user->refresh();
        $this->assertSame('Paris', $user->meteo_city);
        $this->assertFalse(Cache::has('weather'.$user->id));
    }

    public function test_meteo_update_requires_numeric_coordinates(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('profile.store.meteo'), [
            'meteo_city' => 'Paris',
            'meteo_lat' => 'not-a-number',
            'meteo_lon' => 2.3522,
        ]);

        $response->assertSessionHasErrors('meteo_lat');
    }
}
