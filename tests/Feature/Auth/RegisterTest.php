<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['captcha.disable' => true]);
    }

    public function test_the_registration_page_can_be_rendered(): void
    {
        $response = $this->get(route('register'));

        $response->assertOk();
    }

    public function test_an_authenticated_user_visiting_register_is_redirected_home(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('register'));

        $response->assertRedirect(route('home'));
    }

    public function test_a_user_can_register(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'captcha' => 'anything',
        ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'jane@example.com']);
        $this->assertDatabaseHas('recipe_daily_selections', [
            'user_id' => User::where('email', 'jane@example.com')->first()->id,
        ]);
        $response->assertRedirect(route('home'));
    }

    public function test_registration_requires_a_unique_email(): void
    {
        User::factory()->create(['email' => 'jane@example.com']);

        $response = $this->post(route('register'), [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'captcha' => 'anything',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_registration_requires_matching_password_confirmation(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'password',
            'password_confirmation' => 'different',
            'captcha' => 'anything',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertDatabaseMissing('users', ['email' => 'jane@example.com']);
    }

    public function test_the_honeypot_field_silently_rejects_bots(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'captcha' => 'anything',
            'last_name' => 'I am a bot',
        ]);

        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'bot@example.com']);
        $response->assertNoContent();
    }
}
