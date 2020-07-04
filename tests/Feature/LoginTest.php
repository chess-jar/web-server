<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test to ensure the user can view the login form.
     *
     * @return void Returns nothing.
     */
    public function testLoginView()
    {
        $response = $this->get('/login');
        $response->assertSuccessful();
        $response->assertViewIs('auth.login');
    }

    /**
     * Test to ensure the user can not view the login form when authenticated.
     *
     * @return void Returns nothing.
     */
    public function testLoginViewWhileAuthenticated()
    {
        $user = factory(User::class)->make();
        $response = $this->actingAs($user)->get('/login');
        $response->assertRedirect('/home');
    }

    /**
     * Test to ensure the user can view the login form.
     *
     * @return void Returns nothing.
     */
    public function testLoginAuthenticationFlow()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make($password = 'i-love-laravel'),
        ]);
        $response = $this->post('/login', [
            'username' => $user->username,
            'password' => $password,
        ]);
        $response->assertRedirect('/home');
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test to ensure the user can not login with wrong credentials.
     *
     * @return void Returns nothing.
     */
    public function testLoginWithWrongCreds()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make($password = 'i-love-laravel'),
        ]);
        $response = $this->from('/login')->post('/login', [
            'username' => $user->username,
            'password' => 'invalid-password',
        ]);
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('username');
        $this->assertTrue(session()->hasOldInput('username'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /**
     * Test to ensure the remember me functionality works.
     *
     * @return void Returns nothing.
     */
    public function testRememberMeFunctionality()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make($password = 'i-love-laravel'),
        ]);
        $response = $this->post('/login', [
            'username' => $user->username,
            'password' => $password,
            'remember' => 'on',
        ]);
        $response->assertRedirect('/home');
        $response->assertCookie(Auth::guard()->getRecallerName(), vsprintf('%s|%s|%s', [
            $user->id,
            $user->getRememberToken(),
            $user->password,
        ]));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test to ensure the user can not login using a username that does not exists.
     *
     * @return void Returns nothing.
     */
    public function testUserCannotLoginWithUsernameThatDoesNotExist()
    {
        $response = $this->from('/login')->post('/login', [
            'username' => 'nobodySpecialHere',
            'password' => 'invalid-password',
        ]);
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('username');
        $this->assertTrue(session()->hasOldInput('username'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /**
     * Test to ensure the user can logout.
     *
     * @return void Returns nothing.
     */
    public function testUserCanLogout()
    {
        $this->be(factory(User::class)->create());
        $response = $this->post('/logout');
        $response->assertRedirect('/');
        $this->assertGuest();
    }

    /**
     * Test to ensure the user can not logout while not authenticated.
     *
     * @return void Returns nothing.
     */
    public function testUserCannotLogoutWhenNotAuthenticated()
    {
        $response = $this->post('/logout');
        $response->assertRedirect('/');
        $this->assertGuest();
    }
}













