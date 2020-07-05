<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test to ensure the user can view the forgot password page.
     *
     * @return void Returns nothing.
     */
    public function testUserCanViewAnEmailPasswordForm()
    {
        $response = $this->get(route('password.request'));
        $response->assertSuccessful();
        $response->assertViewIs('auth.passwords.email');
    }

    /**
     * Test to ensure the user can view the forgot password page when authenticated.
     *
     * @return void Returns nothing.
     */
    public function testUserCanViewAnEmailPasswordFormWhenAuthenticated()
    {
        $user = factory(User::class)->make();
        $response = $this->actingAs($user)->get(route('password.request'));
        $response->assertSuccessful();
        $response->assertViewIs('auth.passwords.email');
    }

    /**
     * Test to ensure the user recieves an email when resetting their password.
     *
     * @return void Returns nothing.
     */
    public function testUserReceivesAnEmailWithAPasswordResetLink()
    {
        Notification::fake();
        $user = factory(User::class)->create([
            'email' => 'john@example.com',
        ]);
        $response = $this->post(route('password.email'), [
            'email' => 'john@example.com',
        ]);
        $this->assertNotNull($token = DB::table('password_resets')->first());
        Notification::assertSentTo($user, ResetPassword::class, function ($notification, $channels) use ($token) {
            return Hash::check($notification->token, $token->token) === true;
        });
    }

    /**
     * Test to ensure the user does not recieves an email when not registered.
     *
     * @return void Returns nothing.
     */
    public function testUserDoesNotReceiveEmailWhenNotRegistered()
    {
        Notification::fake();
        $response = $this->from(route('password.email'))->post(route('password.email'), [
            'email' => 'nobody@example.com',
        ]);
        $response->assertRedirect(route('password.email'));
        $response->assertSessionHasErrors('email');
        Notification::assertNotSentTo(factory(User::class)->make(['email' => 'nobody@example.com']), ResetPassword::class);
    }

    /**
     * Test to ensure the user does not recieves an email when no email is passed.
     *
     * @return void Returns nothing.
     */
    public function testEmailIsRequired()
    {
        $response = $this->from(route('password.email'))->post(route('password.email'), []);
        $response->assertRedirect(route('password.email'));
        $response->assertSessionHasErrors('email');
    }

    /**
     * Test to ensure the user passes a valid email.
     *
     * @return void Returns nothing.
     */
    public function testEmailIsAValidEmail()
    {
        $response = $this->from(route('password.email'))->post(route('password.email'), [
            'email' => 'invalid-email',
        ]);
        $response->assertRedirect(route('password.email'));
        $response->assertSessionHasErrors('email');
    }
}
