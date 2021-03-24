<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedTest extends TestCase
{
    use RefreshDatabase;

    public function test_feed_shows_recently_added_products()
    {
        $user1 = factory(\App\Models\User::class)->create();
        $user2 = factory(\App\Models\User::class)->create();
        $brand = factory(\App\Models\Brand::class)->create();
        factory(\App\Models\Follower::class)->create(['user_id' => $user1->id, 'follower_id' => $user2->id]);
        factory(\App\Models\Product::class)->create(['user_id' => $user1->id, 'status' => 'published', 'brand_id' => $brand['id']]);
        $response = $this->actingAs($user2)->get('/feed');
        $response->assertStatus(200);
    }

    public function test_error_is_thrown_if_no_brand_is_found()
    {
        $user = factory(\App\Models\User::class)->create();
        $response = $this->actingAs($user)->get('/top-brands');
        $this->assertEquals('No brand found', $response['message']);
        $response->assertStatus(404);
    }

    public function test_home_page_show_top_brands()
    {
        $user = factory(\App\Models\User::class)->create();
        $brand1 = factory(\App\Models\Brand::class)->create();
        $brand2 = factory(\App\Models\Brand::class)->create();
        factory(\App\Models\Product::class, 2)->create(['brand_id' => $brand1->id, 'status' => 'published']);
        factory(\App\Models\Product::class)->create(['brand_id' => $brand2->id, 'status' => 'published']);
        $response = $this->actingAs($user)->get('/top-brands');
        $response->assertStatus(200);
    }

    public function test_error_is_thrown_when_follower_is_not_found_for_user()
    {
        $user = factory(\App\Models\User::class)->create();
        $response = $this->actingAs($user)->get('/popular-followers');
        $this->assertEquals('No follower found', $response['message']);
        $response->assertStatus(404);
    }

    public function test_users_are_returned_even_upon_having_empty_following_list()
    {
        $user = factory(\App\Models\User::class)->create();
        $response = $this->actingAs($user)->get('/people-you-may-know');
        $response->assertStatus(200);

    }
}
