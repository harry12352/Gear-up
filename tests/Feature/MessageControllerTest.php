<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_messages_related_to_a_particular_users_are_fetched()
    {
        $this->withoutExceptionHandling();
        $user = factory(\App\Models\User::class)->create();
        $message = factory(\App\Models\Message::class)->create();
        $user->messages()->attach($message);
        $response = $this->get('/profile/' . $user['username'] . '/messages');
        $response->assertStatus(200);
    }

    public function test_error_thrown_when_no_messages_are_fetched()
    {
        $user = factory(\App\Models\User::class)->create();
        factory(\App\Models\Message::class)->create();
        $response = $this->get('/profile/' . $user['username'] . '/messages');
        $this->assertEquals('No messages found', $response['message']);
        $response->assertStatus(404);
    }

    public function test_messages_are_detach_when_read_by_user()
    {
        $this->withoutExceptionHandling();
        $user = factory(\App\Models\User::class)->create();
        $message = factory(\App\Models\Message::class)->create();
        $user->messages()->attach($message);
        $response = $this->actingAs($user)->get('/profile/' . $user['username'] . '/message/detach/' . $message['id']);
        $this->assertEquals('Message detached successfully', $response['message']);
        $response->assertStatus(200);
    }

    public function test_someone_can_not_read_others_messages()
    {
        $user = factory(\App\Models\User::class)->create();
        $user1 = factory(\App\Models\User::class)->create();
        $message = factory(\App\Models\Message::class)->create();
        $user->messages()->attach($message);
        $response = $this->actingAs($user1)->get('/profile/' . $user['username'] . '/message/detach/' . $message['id']);
        $this->assertEquals('You are not authorized to perform this action', $response['message']);
        $response->assertStatus(403);
    }

    public function test_a_user_can_not_detach_a_message_that_has_not_been_attached_to_him()
    {
        $user = factory(\App\Models\User::class)->create();
        $message = factory(\App\Models\Message::class)->create();
        $response = $this->actingAs($user)->get('/profile/' . $user['username'] . '/message/detach/' . $message['id']);
        $this->assertEquals('You are not authorized to perform this action', $response['message']);
        $response->assertStatus(403);
    }
}
