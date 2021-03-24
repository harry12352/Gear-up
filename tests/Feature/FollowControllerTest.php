<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use App\Notifications\NewFollower;
use App\Models\User;


class FollowControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_follow_user_first_time()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create([
            "username" => 'test123',
            'password' => Hash::make('asdxzc123%')
        ]);

        $user2 = factory(User::class)->create([
            "username" => 'test321',
            'password' => Hash::make('asdxzc123%')
        ]);

        $response = $this->actingAs($user2)->post(route("follow.user", ["user" => $user->username]));
        $response->assertStatus(200);
        $this->assertDatabaseHas("followers", [
            "user_id" => $user->id,
            "follower_id" => $user2->id
        ]);
    }

    public function test_notification_sent_on_newfollower()
    {
        Notification::fake();
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create([
            "username" => 'test123',
            'password' => Hash::make('asdxzc123%')
        ]);

        $user2 = factory(User::class)->create([
            "username" => 'test321',
            'password' => Hash::make('asdxzc123%')
        ]);

        $response = $this->actingAs($user2)->post(route("follow.user", ["user" => $user->username]));
        $response->assertStatus(200);
        $this->assertDatabaseHas("followers", [
            "user_id" => $user->id,
            "follower_id" => $user2->id
        ]);
        Notification::assertSentTo(
            [$user], NewFollower::class
        );
    }

    public function test_follow_without_loggedin()
    {
        $this->withExceptionHandling();

        $user = factory(User::class)->create([
            "username" => 'test123',
            'password' => Hash::make('asdxzc123%')
        ]);

        $response = $this->post(route("follow.user", ["user" => $user->username]));
        $response->assertRedirect(route("login"));
    }

    public function test_unfollow_user()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create([
            "username" => 'test123',
            'password' => Hash::make('asdxzc123%')
        ]);

        $user2 = factory(User::class)->create([
            "username" => 'test321',
            'password' => Hash::make('asdxzc123%')
        ]);

        // Following user.
        $response = $this->actingAs($user2)->post(route("follow.user", ["user" => $user->username]));
        $response->assertStatus(200);
        $this->assertDatabaseHas("followers", [
            "user_id" => $user->id,
            "follower_id" => $user2->id
        ]);

        // Un-Following user.
        $response = $this->actingAs($user2)->post(route("unfollow.user", ["user" => $user->username]));
        $response->assertStatus(200);

        $this->assertEmpty($user2->following);
    }

    public function test_unfollow_user_again()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create([
            "username" => 'test123',
            'password' => Hash::make('asdxzc123%')
        ]);

        $user2 = factory(User::class)->create([
            "username" => 'test321',
            'password' => Hash::make('asdxzc123%')
        ]);

        // Un-Following user, without following.
        $response = $this->actingAs($user2)->post(route("unfollow.user", ["user" => $user->username]));
        $response->assertStatus(302)
                ->assertSessionHas(["error"]);

        $this->assertEmpty($user2->following);
    }

    public function test_follow_user_again()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create([
            "username" => 'test123',
            'password' => Hash::make('asdxzc123%')
        ]);

        $user2 = factory(User::class)->create([
            "username" => 'test321',
            'password' => Hash::make('asdxzc123%')
        ]);

        // Following user first time.
        $response = $this->actingAs($user2)->post(route("follow.user", ["user" => $user->username]));
        $response->assertStatus(200);

        $this->assertEquals(count($user->followers), 1) ;

        $response = $this->actingAs($user2)->post(route("follow.user", ["user" => $user->username]));
        $response->assertStatus(403);

        $this->assertEquals(count($user->followers), 1);
    }

}
