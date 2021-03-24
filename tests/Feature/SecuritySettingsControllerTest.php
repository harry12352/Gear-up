<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;

class SecuritySettingsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_update_password_form()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->get(route("settings.security"));
        $response->assertStatus(200);
    }

    public function test_update_password()
    {
        $userPassword = "asdzxc1234$";
        $user = factory(User::class)->create(["password" => Hash::make($userPassword)]);

        $payload = [
            "password"  => "asdzxc321$",
            "password_confirmation"  => "asdzxc321$",
            "old_password"  => $userPassword,
        ];
        $response = $this->actingAs($user)->post(route("settings.security"), $payload);
        $response->assertRedirect(route("settings.security"))
                ->assertSessionDoesntHaveErrors();
    }

    public function test_update_password_incorrect_old_password()
    {
        $userPassword = "asdzxc1234$";
        $user = factory(User::class)->create(["password" => Hash::make($userPassword)]);

        $payload = [
            "password"  => "asdzxc321$",
            "password_confirmation"  => "asdzxc321$"
        ];
        // Going to settings security page so that we get redirected correctly
        // on validation error.
        $this->get(route("settings.security"));

        // Making request to update password.
        $response = $this->actingAs($user)->post(route("settings.security"), $payload);
        $response->assertRedirect(route("settings.security"))
                ->assertSessionHasErrors(["old_password"]);
    }

    public function test_update_password_empty_new_password()
    {
        $userPassword = "asdzxc1234$";
        $user = factory(User::class)->create(["password" => Hash::make($userPassword)]);

        $payload = [
            "old_password"  => $userPassword
        ];
        // Going to settings security page so that we get redirected correctly
        // on validation error.
        $this->get(route("settings.security"));

        // Making request to update password.
        $response = $this->actingAs($user)->post(route("settings.security"), $payload);
        $response->assertRedirect(route("settings.security"))
                ->assertSessionHasErrors(["password"]);
    }
}
