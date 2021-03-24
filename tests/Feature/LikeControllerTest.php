<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_like_product()
    {
        $user = factory(\App\Models\User::class)->create();
        factory(\App\Models\Product::class)->create(['status' => 'published','user_id'=>1]);
        $productId = Product::first()->id;
        $response = $this->actingAs($user)->get('product/' . $productId . '/like');
        $this->assertEquals('Product has been like', $response['message']);
        $response->assertStatus(200);
    }

    public function test_a_user_can_unlike_product()
    {
        $user = factory(\App\Models\User::class)->create();
        $product = factory(\App\Models\Product::class)->create(['status' => 'published']);
        factory(\App\Models\Like::class)->create(['user_id' => $user->id, 'product_id' => $product->id]);
        $productId = Product::first()->id;
        $response = $this->actingAs($user)->get('product/' . $productId . '/unlike');
        $this->assertEquals('Product has been Unliked', $response['message']);
        $response->assertStatus(200);

    }

    public function test_a_user_can_not_like_a_product_multiple_times()
    {
        $user = factory(\App\Models\User::class)->create();
        $product = factory(\App\Models\Product::class)->create(['status' => 'published']);
        factory(\App\Models\Like::class)->create(['user_id' => $user->id, 'product_id' => $product->id]);
        $productId = Product::first()->id;
        $response = $this->actingAs($user)->get('product/' . $productId . '/like');
        $this->assertEquals('You have already liked this product', $response['message']);
        $response->assertStatus(401);
    }

    public function test_a_user_can_not_unlike_product_that_has_not_been_liked_before()
    {
        factory(\App\Models\Product::class)->create(['status' => 'published']);
        $user = factory(\App\Models\User::class)->create();
        $productId = Product::first()->id;
        $response = $this->actingAs($user)->get('product/' . $productId . '/unlike');
        $this->assertEquals('You have not access to perform this action', $response['message']);
        $response->assertStatus(401);
    }

    public function test_a_user_can_not_like_a_product_that_does_not_exists()
    {
        $user = factory(\App\Models\User::class)->create();
        $response = $this->actingAs($user)->get('product/1/like');
        $response->assertStatus(404);
    }

    public function test_a_user_can_not_unlike_a_product_that_does_not_exists()
    {
        $user = factory(\App\Models\User::class)->create();
        $response = $this->actingAs($user)->get('product/1/unlike');
        $response->assertStatus(404);
    }

    public function test_a_user_can_not_like_a_product_that_is_in_drafted_mode()
    {
        $user = factory(\App\Models\User::class)->create();
        factory(\App\Models\Product::class)->create(['status' => 'drafted']);
        $productId = Product::first()->id;
        $response = $this->actingAs($user)->get('product/' . $productId . '/like');
        $this->assertEquals('You can not like a product that is in drafted mode', $response['message']);
        $response->assertStatus(403);
    }

    public function test_when_a_user_likes_a_product_a_notification_is_sent_to_all_of_his_followers_and_the_product_owner()
    {
        $user = factory(\App\Models\User::class)->create();
        $user1 = factory(\App\Models\User::class)->create();
        $user2 = factory(\App\Models\User::class)->create();
        factory(\App\Models\Follower::class)->create(['user_id' => $user->id, 'follower_id' => $user1->id]);
        factory(\App\Models\Follower::class)->create(['user_id' => $user->id, 'follower_id' => $user2->id]);
        $product = factory(\App\Models\Product::class)->create(['status' => 'published', 'user_id' => $user1->id]);
        $response = $this->actingAs($user)->get('/product/' . $product->id . '/like');
        $response->assertStatus(200);
    }

}
