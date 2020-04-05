<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Support\Facades\Route;
use template\Domain\Users\Profiles\Profile;
use template\Domain\Users\Users\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegisterControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testToVisitRegister()
    {
        if (!Route::has('register')) {
            $this->markTestSkipped('route "register" is not defined');
        }

        $this
            ->get('/register')
            ->assertSuccessful()
            ->assertSeeText('Registration Form')
            ->assertSee('Email')
            ->assertSee('Password')
            ->assertSee('Confirm password')
            ->assertSeeText('Register');
    }

    public function testToSubmitRegister()
    {
        if (!Route::has('register')) {
            $this->markTestSkipped('route "register" is not defined');
        }

        $email = $this->faker->email;
        $profile = factory(Profile::class)->make();
        $this
            ->from('/register')
            ->post('/register', $profile->toArray() + [
                    'email' => $email,
                    'password' => $this->getDefaultPassword(),
                    'password_confirmation' => $this->getDefaultPassword()
                ])
            ->assertStatus(302)
            ->assertRedirect('/');
        $this->assertDatabaseHas('users', [
            'email' => $email,
        ]);
    }

    public function testToSubmitRegisterWithInvalidEmail()
    {
        if (!Route::has('register')) {
            $this->markTestSkipped('route "register" is not defined');
        }

        $email = $this->faker->word;
        $profile = factory(Profile::class)->make();
        $this
            ->followingRedirects()
            ->from('/register')
            ->post('/register', $profile->toArray() + [
                    'email' => $email,
                    'password' => $this->getDefaultPassword(),
                    'password_confirmation' => $this->getDefaultPassword()
                ])
            ->assertSuccessful()
            ->assertSeeText('The email must be a valid email address.');
        $this->assertDatabaseMissing('users', [
            'email' => $email,
        ]);
    }
}
