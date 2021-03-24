<?php

namespace Tests\Feature;

use App\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FollowBrandControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_follow_a_brand()
    {
        $this->withoutExceptionHandling();
        factory(\App\Models\Brand::class)->create();
        $user = factory(\App\Models\User::class)->create();
        $response = $this->actingAs($user)->get('/brand/' . Brand::first()['id'] . '/follow');
        $this->assertEquals('Brand has been followed successfully', $response['message']);
    }

    public function test_a_user_can_not_follow_a_brand_that_has_been_followed_before()
    {
        $brand = factory(\App\Models\Brand::class)->create();
        $user = factory(\App\Models\User::class)->create();
        factory(\App\Models\FollowBrand::class)->create(['user_id' => $user['id'], 'brand_id' => $brand['id']]);
        $response = $this->actingAs($user)->get('/brand/' . Brand::first()['id'] . '/follow');
        $this->assertEquals('You You have already followed this Brand', $response['message']);
    }

    public function test_a_user_can_unfollow_a_brand()
    {
        $brand = factory(\App\Models\Brand::class)->create();
        $user = factory(\App\Models\User::class)->create();
        factory(\App\Models\FollowBrand::class)->create(['user_id' => $user['id'], 'brand_id' => $brand['id']]);
        $response = $this->actingAs($user)->get('/brand/' . Brand::first()['id'] . '/unfollow');
        $this->assertEquals('Brand has been unfollowed successfully', $response['message']);
    }

    public function test_a_user_can_not_unfollow_a_brand_that_has_not_been_followed_before()
    {
        factory(\App\Models\Brand::class)->create();
        $user = factory(\App\Models\User::class)->create();
        $response = $this->actingAs($user)->get('/brand/' . Brand::first()['id'] . '/unfollow');
        $this->assertEquals('You have not access to perform this action', $response['message']);
    }

    public function test_a_user_can_not_follow_a_brand_that_has_been_not_created()
    {
        $user = factory(\App\Models\User::class)->create();
        $response = $this->actingAs($user)->get('/brand/222/follow');
        $response->assertStatus(404);
    }

    public function test_a_user_can_not_unfollow_a_brand_that_has_been_not_created()
    {
        $user = factory(\App\Models\User::class)->create();
        $response = $this->actingAs($user)->get('/brand/222/unfollow');
        $response->assertStatus(404);
    }

    public function test_a_page_shows_all_products_related_to_brand()
    {
        $this->withoutExceptionHandling();
        $user = factory(\App\Models\User::class)->create();
        factory(\App\Models\Brand::class)->create();
        factory(\App\Models\Product::class, 10)->create(['brand_id' => Brand::first()['id'], 'status' => 'published', 'user_id' => $user['id']]);
        $response = $this->actingAs($user)->get('/brand/' . Brand::first()['slug']);
        $response->assertViewIs('brand.view');
    }
}
