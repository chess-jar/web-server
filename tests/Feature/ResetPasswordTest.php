<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    /**
     * Get a valid token from the password broker.
     *
     * @return string Returns a valid token.
     */
    protected function getValidToken($user)
    {
        return Password::broker()->createToken($user);
    }

    /**
     * Test to ensure the user can view the password reset form.
     *
     * @return void Returns nothing.
     */
    public function testUserCanViewAPasswordResetForm()
    {
        $user = factory(User::class)->create();
        $response = $this->get(route('password.reset', $token = $this->getValidToken($user)));
        $response->assertSuccessful();
        $response->assertViewIs('auth.passwords.reset');
        $response->assertViewHas('token', $token);
    }

    /**
     * Test to ensure the user can view the password reset form when authenticated.
     *
     * @return void Returns nothing.
     */
    public function testUserCanViewAPasswordResetFormWhenAuthenticated()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->get(route('password.reset', $token = $this->getValidToken($user)));
        $response->assertSuccessful();
        $response->assertViewIs('auth.passwords.reset');
        $response->assertViewHas('token', $token);
    }

    /**
     * Test to ensure the user can view the password reset form when authenticated.
     *
     * @return void Returns nothing.
     */
    public function testUserCanResetPasswordWithValidToken()
    {
        Event::fake();
        $user = factory(User::class)->create();
        $response = $this->post('/password/reset', [
            'token'                 => $this->getValidToken($user),
            'email'                 => $user->email,
            'password'              => 'new-awesome-password',
            'password_confirmation' => 'new-awesome-password',
        ]);
        $response->assertRedirect('/home');
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('new-awesome-password', $user->fresh()->password));
        $this->assertAuthenticatedAs($user);
        Event::assertDispatched(PasswordReset::class, function ($e) use ($user) {
            return $e->user->id === $user->id;
        });
    }

    /**
     * Test to ensure the user can not reset password with invalid token.
     *
     * @return void Returns nothing.
     */
    public function testUserCannotResetPasswordWithInvalidToken()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make('old-password'),
        ]);
        $response = $this->from(route('password.reset', 'invalid-token'))->post('/password/reset', [
            'token'                 => 'invalid-token',
            'email'                 => $user->email,
            'password'              => 'new-awesome-password',
            'password_confirmation' => 'new-awesome-password',
        ]);
        $response->assertRedirect(route('password.reset', 'invalid-token'));
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
        $this->assertGuest();
    }

    /**
     * Test to ensure the user can not reset password with invalid token.
     *
     * @return void Returns nothing.
     */
    public function testUserCannotResetPasswordWithoutProvidingANewPassword()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make('old-password'),
        ]);
        $response = $this->from(route('password.reset', $token = $this->getValidToken($user)))->post('/password/reset', [
            'token'                 => $token,
            'email'                 => $user->email,
            'password'              => '',
            'password_confirmation' => '',
        ]);
        $response->assertRedirect(route('password.reset', $token));
        $response->assertSessionHasErrors('password');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
        $this->assertGuest();
    }

    /**
     * Test to ensure the user can not reset password with invalid token.
     *
     * @return void Returns nothing.
     */
    public function testUserCannotResetPasswordWithoutProvidingAnEmail()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make('old-password'),
        ]);
        $response = $this->from(route('password.reset', $token = $this->getValidToken($user)))->post('/password/reset', [
            'token'                 => $token,
            'email'                 => '',
            'password'              => 'new-awesome-password',
            'password_confirmation' => 'new-awesome-password',
        ]);
        $response->assertRedirect(route('password.reset', $token));
        $response->assertSessionHasErrors('email');
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
        $this->assertGuest();
    }
}
