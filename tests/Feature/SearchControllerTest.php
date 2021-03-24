<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_when_the_product_is_found_view_is_returned_for_search_results()
    {
        $user = factory(\App\Models\User::class)->create();
        $brand = factory(\App\Models\Brand::class)->create();
        factory(Product::class)->create(['title' => 'bag', 'status' => 'published', 'user_id' => $user['id'], 'brand_id' => $brand['id']]);
        $response = $this->get('/search?q=bag');
        $response->assertViewIs('profile.products.search-result');
    }

    public function test_when_the_search_field_is_empty_no_exception_thrown()
    {
        $brand = factory(\App\Models\Brand::class)->create();
        $user = factory(\App\Models\User::class)->create();
        factory(Product::class)->create(['title' => 'bag', 'status' => 'published', 'user_id' => $user['id'], 'brand_id' => $brand['id']]);
        $response = $this->get('/search?q=');
        $response->assertViewIs('profile.products.search-result');
    }

    public function test_when_the_a_product_not_present_database_is_searched_no_exception_thrown()
    {
        $brand = factory(\App\Models\Brand::class)->create();
        $user = factory(\App\Models\User::class)->create();
        factory(Product::class)->create(['title' => 'bag', 'status' => 'published', 'user_id' => $user['id'], 'brand_id' => $brand['id']]);
        $response = $this->get('/search?q=random');
        $response->assertViewIs('profile.products.search-result');
    }
}
