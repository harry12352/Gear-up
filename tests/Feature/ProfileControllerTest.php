<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_profile_is_accessible()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create([
            "username" => 'test123',
            'password' => Hash::make('asdxzc123%')
        ]);

        $response = $this->get(route("profile.index", ["user" => $user]));

        $response->assertStatus(200);
    }

    public function test_access_invalid_profile()
    {
        $user = new \stdClass();
        $user->username = $this->faker->userName;
        $response = $this->get(route("profile.index", ["user" => $user->username]));
        $response->assertStatus(404);
    }
}
