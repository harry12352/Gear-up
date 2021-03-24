<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FollowCategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_follow_category()
    {
        $user = factory(\App\Models\User::class)->create();
        $category = factory(\App\Models\Category::class)->create();
        $response = $this->actingAs($user)->get('/category/' . $category['id'] . '/follow');
        $this->assertEquals('Category has been followed', $response['message']);
        $response->assertStatus(200);
    }

    public function test_a_user_can_not_unfollow_a_category_that_has_not_been_followed_before()
    {
        $user = factory(\App\Models\User::class)->create();
        $category = factory(\App\Models\Category::class)->create();
        $response = $this->actingAs($user)->get('/category/' . $category['id'] . '/unfollow');
        $this->assertEquals('You have not access to perform this action', $response['message']);
        $response->assertStatus(401);
    }

    public function test_a_user_can_unfollow_category()
    {

        $user = factory(\App\Models\User::class)->create();
        $category = factory(\App\Models\Category::class)->create();
        factory(\App\Models\FollowCategory::class)->create(['user_id' => $user['id'], 'category_id' => $category['id']]);
        $response = $this->actingAs($user)->get('/category/' . $category['id'] . '/unfollow');
        $this->assertEquals('Category has been unfollowed', $response['message']);
        $response->assertStatus(200);
    }

    public function test_a_user_can_not_follow_a_category_that_has_been_followed_before()
    {
        $user = factory(\App\Models\User::class)->create();
        $category = factory(\App\Models\Category::class)->create();
        factory(\App\Models\FollowCategory::class)->create(['user_id' => $user['id'], 'category_id' => $category['id']]);
        $response = $this->actingAs($user)->get('/category/' . $category['id'] . '/follow');
        $this->assertEquals('You have already followed this Category', $response['message']);
        $response->assertStatus(401);
    }

    public function test_a_user_can_not_follow_a_category_that_has_been_not_created()
    {
        $user = factory(\App\Models\User::class)->create();
        $response = $this->actingAs($user)->get('/category/222/follow');
        $response->assertStatus(404);
    }

    public function test_a_user_can_not_unfollow_a_category_that_has_been_not_created()
    {
        $user = factory(\App\Models\User::class)->create();
        $response = $this->actingAs($user)->get('/category/222/unfollow');
        $response->assertStatus(404);
    }

    public function test_a_page_shows_all_products_related_to_category()
    {
        $user = factory(\App\Models\User::class)->create();
        $category = factory(\App\Models\Category::class)->create(['slug' => 'Bailey-Mante']);
        $brand = factory(\App\Models\Brand::class)->create();
        $product = factory(\App\Models\Product::class)->create(['status' => 'published', 'brand_id' => $brand['id'], 'user_id' => $user['id']]);
        $category->products()->attach($product);
        $response = $this->actingAs($user)->get('/category/Bailey-Mante');
        $response->assertViewIs('category.view');
    }
}
