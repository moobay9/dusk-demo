<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Str;
use App\Models\User;

class RegisterTest extends DuskTestCase
{
    /**
     * A Dusk test Page.
     */
    public function testPageLanguage(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                    ->assertSee(__('Name'))
                    ->assertSee(__('Email'))
                    ->assertSee(__('Password'))
                    ->assertSee(__('Confirm Password'))
                    ->assertSee(__('Already registered?'));
        });
    }

    /**
     * A Dusk test Register.
     */
    public function testRegister(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                    ->typeSlowly('name', 'Test User')
                    ->typeSlowly('email', Str::of(Str::random(10))->lower().'@testtesttest.co.jp')
                    ->typeSlowly('password', 'password')
                    ->typeSlowly('password_confirmation', 'password')
                    ->press(__('REGISTER'))
                    ->assertPathIs('/dashboard')
                    ->click('@menu-button')
                    ->click('@logout-button');
            });
    }

    /**
     * A Dusk test Register.
     */
    public function testRegisterError(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                    ->type('name', Str::random(256))
                    ->type('email', Str::of(Str::random(240))->lower().'@testtesttest.co.jp')
                    ->type('password', 'password')
                    ->type('password_confirmation', 'password1')
                    ->press(__('REGISTER'))
                    ->assertSee(__('The name field must not be greater than 255 characters.'))
                    ->assertSee(__('The email field must not be greater than 255 characters.'))
                    ->assertSee(__('The password field confirmation does not match.'));


        });
    }

    /**
     * A Dusk test Login and Logout.
     */
    public function testLoginLogout(): void
    {
        $user = User::factory()->create([
            'email' => Str::of(Str::random(20))->lower().'@testtesttest.co.jp',
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'password')
                    ->press('LOG IN')
                    ->assertPathIs('/dashboard')
                    ->click('@menu-button')
                    ->click('@logout-button');
            });
    }

}
