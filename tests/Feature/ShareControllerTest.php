<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShareControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_share_a_product()
    {
        $user = factory(\App\Models\User::class)->create();
        factory(\App\Models\Product::class)->create(['status' => 'published', 'user_id' => 1]);
        $productId = Product::first()->id;
        $response = $this->actingAs($user)->get('/product/' . $productId . '/share');
        $this->assertEquals('You have successfully shared this product', $response['message']);
        $response->assertStatus(200);
    }

    public function test_a_user_can_not_share_non_existing_product()
    {
        $user = factory(\App\Models\User::class)->create();
        $response = $this->actingAs($user)->get('/product/1/share');
        $response->assertStatus(404);
    }

    public function test_a_user_can_not_share_a_drafted_product()
    {
        $user = factory(\App\Models\User::class)->create();
        factory(\App\Models\Product::class)->create(['status' => 'drafted']);
        $productId = Product::first()->id;
        $response = $this->actingAs($user)->get('/product/' . $productId . '/share');
        $this->assertEquals('You can not share a product that is in drafted mode', $response['message']);
        $response->assertStatus(403);
    }

    public function test_when_a_user_shares_a_product_a_notification_is_sent_to_all_of_his_followers_and_the_product_owner()
    {
        $user = factory(\App\Models\User::class)->create();
        $user1 = factory(\App\Models\User::class)->create();
        $user2 = factory(\App\Models\User::class)->create();
        factory(\App\Models\Follower::class)->create(['user_id' => $user->id, 'follower_id' => $user1->id]);
        factory(\App\Models\Follower::class)->create(['user_id' => $user->id, 'follower_id' => $user2->id]);
        $product = factory(\App\Models\Product::class)->create(['status' => 'published', 'user_id' => $user1->id]);
        $response = $this->actingAs($user)->get('/product/' . $product->id . '/share');;
        $response->assertStatus(200);
    }
}
