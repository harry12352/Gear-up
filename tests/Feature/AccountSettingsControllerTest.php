<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\User;

class AccountSettingsControllerTest extends TestCase {

    use RefreshDatabase;

    public function test_update_user_account_name() {

        $this->withoutExceptionHandling();

        $user = factory(User::class)->create([
            "username" => 'test123',
            'password' => Hash::make('asdxzc123')
        ]);
        $updatedUser = factory(User::class)->make();

        // New data to update.
        $payload = [
            'first_name' => $updatedUser->first_name,
            'last_name' => $updatedUser->last_name,
            'email' => $user->email,
        ];

        $response = $this->actingAs($user)->post('/settings/account', $payload);

        $response->assertStatus(302)
                 ->assertSessionHas("success");

        $this->assertDatabaseHas("users", [
            'first_name' => $updatedUser->first_name,
            'last_name' => $updatedUser->last_name,
        ]);
    }

    public function test_update_user_email()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create([
            "username" => 'test123',
            'password' => Hash::make('asdxzc123')
        ]);
        $updatedUser = factory(User::class)->make();

        // New data to update.
        $payload = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $updatedUser->email,
        ];

        $response = $this->actingAs($user)->post('/settings/account', $payload);

        $response->assertStatus(302)
                 ->assertSessionHas("success");

        $this->assertDatabaseHas("users", [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $updatedUser->email,
        ]);
    }

    public function test_update_user_bio()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create([
            "username" => 'test123',
            'password' => Hash::make('asdxzc123')
        ]);
        $updatedUser = factory(User::class)->make();

        // New data to update.
        $payload = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'bio' => $updatedUser->bio,
        ];

        $response = $this->actingAs($user)->post('/settings/account', $payload);

        $response->assertStatus(302)
                 ->assertSessionHas("success");

        $this->assertDatabaseHas("users", [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'bio' => $updatedUser->bio,
        ]);

    }

    public function test_update_profile_image()
    {
        Storage::fake('public');

        $this->withoutExceptionHandling();

        $user = factory(User::class)->create([
            "username" => 'test123',
            'password' => Hash::make('asdxzc123')
        ]);
        $updatedUser = factory(User::class)->make();
        $profilePictureName = "profilepicture.jpeg";
        $userProfilePath = "images/" . $profilePictureName;

        // New data to update.
        $payload = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'bio' => $user->bio,
            "profile_image" => UploadedFile::fake()->image($profilePictureName)
        ];

        $response = $this->actingAs($user)->post('/settings/account', $payload);
        // dd(User::all()->count());
        $response->assertStatus(302)
                 ->assertSessionHas("success");
         Storage::disk("public")->assertExists($userProfilePath);
    }
}
