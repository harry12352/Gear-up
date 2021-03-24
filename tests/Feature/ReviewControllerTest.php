<?php

namespace Tests\Feature;

use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_give_reviews_on_the_products_he_purchased()
    {
        $user = factory(\App\Models\User::class)->create();
        $product = factory(\App\Models\Product::class)->create(['status' => 'published']);
        $response = $this->actingAs($user)->post('/product/' . $product['id'] . '/review/new', ['content' => 'nice product', 'rating' => 2]);
        $this->assertEquals('You have successfully reviewed on this product', $response['message']);
        $response->assertStatus(200);
    }

    public function test_a_user_can_not_give_multiple_reviews_on_the_products_he_purchased()
    {
        $user = factory(\App\Models\User::class)->create();
        $product = factory(\App\Models\Product::class)->create(['status' => 'published']);
        factory(\App\Models\Review::class)->create(['user_id' => $user['id'], 'product_id' => $product['id']]);
        $response = $this->actingAs($user)->post('/product/' . $product['id'] . '/review/new', ['content' => 'nice product', 'rating' => 2]);
        $this->assertEquals('You have already reviewed on this product', $response['message']);
        $response->assertStatus(403);
    }
}
