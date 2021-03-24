<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_user_complete_information()
    {
//        $this->withoutExceptionHandling();

        $user = factory(User::class)->make(["username" => "890asd"]);

        $payload = [
            'username' => $user->username,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'password' => "asdzxc123%",
            'password_confirmation' => "asdzxc123%",
        ];

        $response = $this->post('/register', $payload);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);

        $this->assertDatabaseHas("users", [
            'username' => $user->username,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
        ]);
    }

    public function test_register_user_same_username_error()
    {
        $user = factory(User::class)->create();

        $payload = [
            'username' => $user->username,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'password' => "asdzxc123%",
            'password_confirmation' => "asdzxc123%",
        ];

        $response = $this->post('/register', $payload);

        $response = $this->post('/register', $payload);
        $response->assertSessionHasErrors(["username"])
                 ->assertStatus(302);
    }

    public function test_register_user_same_email_error()
    {
        $user = factory(User::class)->create();

        $payload = [
            'username' => $user->username . "asd",
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'password' => "asdzxc123%",
            'password_confirmation' => "asdzxc123%",
        ];

        $response = $this->post('/register', $payload);

        $response = $this->post('/register', $payload);
        $response->assertSessionHasErrors(["email"])
                 ->assertStatus(302);
    }

    public function test_register_user_username_validation_error()
    {
        $user = factory(User::class)->make();
        $payload = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'password' => "asdzxc123%",
            'password_confirmation' => "asdzxc123%",
        ];

        $response = $this->post('/register', $payload);
        $response->assertSessionHasErrors(["username"])
                 ->assertStatus(302);
    }

    public function test_username_with_spaces_truncated()
    {
        $user = factory(User::class)->make();
        $username = "asd sad";
        $payload = [
            'username' => "asd sad",
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'password' => "asdzxc123%",
            'password_confirmation' => "asdzxc123%",
        ];

        $response = $this->post('/register', $payload);
        $response->assertStatus(302)
                ->assertSessionHasErrors();
    }

    public function test_correct_username()
    {
        $user = factory(User::class)->make();
        $payload = [
            'username' => "asdsad",
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'password' => "asdzxc123%",
            'password_confirmation' => "asdzxc123%",
        ];

        $response = $this->post('/register', $payload);
        $response->assertStatus(302);
        $this->assertDatabaseHas("users", [
            'username' => "asdsad",
        ]);
    }
}
