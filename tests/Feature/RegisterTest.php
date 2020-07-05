<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test to ensure the user can view the register form.
     *
     * @return void Returns nothing.
     */
    public function testUserCanViewARegistrationForm()
    {
        $response = $this->get('/register');
        $response->assertSuccessful();
        $response->assertViewIs('auth.register');
    }

    /**
     * Test to ensure the user can not view the register form when authenticated.
     *
     * @return void Returns nothing.
     */
    public function testUserCannotViewARegistrationFormWhenAuthenticated()
    {
        $user = factory(User::class)->make();
        $response = $this->actingAs($user)->get('/register');
        $response->assertRedirect('/home');
    }

    /**
     * Test the authentication flow.
     *
     * @return void Returns nothing.
     */
    public function testUserCanRegister()
    {
        Event::fake();
        Role::create(['name' => 'user']);
        $response = $this->post('/register', [
            'username'              => 'volkron',
            'email'                 => 'volkron@example.com',
            'password'              => 'i-love-laravel',
            'password_confirmation' => 'i-love-laravel',
        ]);
        $response->assertRedirect('/home');
        $this->assertCount(1, $users = User::all());
        $this->assertAuthenticatedAs($user = $users->first());
        $this->assertEquals('volkron', $user->username);
        $this->assertEquals('volkron@example.com', $user->email);
        $this->assertTrue(Hash::check('i-love-laravel', $user->password));
        Event::assertDispatched(Registered::class, function ($e) use ($user) {
            return $e->user->id === $user->id;
        });
    }

    /**
     * Test to ensure the user can not register when username field is missing.
     *
     * @return void Returns nothing.
     */
    public function testUserCannotRegisterWithoutName()
    {
        Role::create(['name' => 'user']);
        $response = $this->from('/register')->post('/register', [
            'username'              => '',
            'email'                 => 'john@example.com',
            'password'              => 'i-love-laravel',
            'password_confirmation' => 'i-love-laravel',
        ]);
        $users = User::all();
        $this->assertCount(0, $users);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('username');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /**
     * Test to ensure the user can not register when email field is missing.
     *
     * @return void Returns nothing.
     */
    public function testUserCannotRegisterWithoutEmail()
    {
        Role::create(['name' => 'user']);
        $response = $this->from('/register')->post('/register', [
            'username'              => 'volkron',
            'email'                 => '',
            'password'              => 'i-love-laravel',
            'password_confirmation' => 'i-love-laravel',
        ]);
        $users = User::all();
        $this->assertCount(0, $users);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('email');
        $this->assertTrue(session()->hasOldInput('username'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /**
     * Test to ensure the user can not register when email field is invalid.
     *
     * @return void Returns nothing.
     */
    public function testUserCannotRegisterWithInvalidEmail()
    {
        Role::create(['name' => 'user']);
        $response = $this->from('/register')->post('/register', [
            'username'              => 'volkron',
            'email'                 => 'invalid-email',
            'password'              => 'i-love-laravel',
            'password_confirmation' => 'i-love-laravel',
        ]);
        $users = User::all();
        $this->assertCount(0, $users);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('email');
        $this->assertTrue(session()->hasOldInput('username'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /**
     * Test to ensure the user can not register when password field is missing.
     *
     * @return void Returns nothing.
     */
    public function testUserCannotRegisterWithoutPassword()
    {
        Role::create(['name' => 'user']);
        $response = $this->from('/register')->post('/register', [
            'username'              => 'volkron',
            'email'                 => 'john@example.com',
            'password'              => '',
            'password_confirmation' => '',
        ]);
        $users = User::all();
        $this->assertCount(0, $users);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('password');
        $this->assertTrue(session()->hasOldInput('username'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /**
     * Test to ensure the user can not register when password confirmation field is missing.
     *
     * @return void Returns nothing.
     */
    public function testUserCannotRegisterWithoutPasswordConfirmation()
    {
        Role::create(['name' => 'user']);
        $response = $this->from('/register')->post('/register', [
            'username'              => 'volkron',
            'email'                 => 'john@example.com',
            'password'              => 'i-love-laravel',
            'password_confirmation' => '',
        ]);
        $users = User::all();
        $this->assertCount(0, $users);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('password');
        $this->assertTrue(session()->hasOldInput('username'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /**
     * Test to ensure the user can not register when the password don't match.
     *
     * @return void Returns nothing.
     */
    public function testUserCannotRegisterWithPasswordsNotMatching()
    {
        Role::create(['name' => 'user']);
        $response = $this->from('/register')->post('/register', [
            'username'              => 'volkron',
            'email'                 => 'john@example.com',
            'password'              => 'i-love-laravel',
            'password_confirmation' => 'i-love-symfony',
        ]);
        $users = User::all();
        $this->assertCount(0, $users);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('password');
        $this->assertTrue(session()->hasOldInput('username'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }
}