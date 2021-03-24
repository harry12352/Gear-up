<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_are_fetched_related_to_brands_when_size_is_given()
    {
        $user = factory(\App\Models\User::class)->create();
        $brand = factory(\App\Models\Brand::class)->create();
        $size = factory(\App\Models\Size::class)->create(['name' => 'ExtraSmall']);
        factory(\App\Models\Product::class, 2)->create(['user_id' => $user['id'], 'status' => 'published', 'size_id' => 101, 'brand_id' => $brand['id'], 'price' => 200]);
        factory(\App\Models\Product::class)->create(['user_id' => $user['id'], 'status' => 'published', 'brand_id' => $brand['id'], 'size_id' => $size['id'], 'price' => 100]);
        $response = $this->get('/filter?brand=' . $brand['id'] . '&size=' . $size['id']);
        $response->assertJsonCount(1, 'data');
        $response->assertStatus(200);

    }

    public function test_products_are_fetched_related_to_brands_when_color_is_given()
    {
        $user = factory(\App\Models\User::class)->create();
        $brand = factory(\App\Models\Brand::class)->create();
        factory(\App\Models\Product::class, 2)->create(['user_id' => $user['id'], 'status' => 'published', 'brand_id' => $brand['id'], 'price' => 200]);
        $product = factory(\App\Models\Product::class)->create(['user_id' => $user['id'], 'status' => 'published', 'brand_id' => $brand['id'], 'price' => 100]);
        $color = factory(\App\Models\Color::class)->create(['name' => 'red', 'value' => 'red']);
        $product->colors()->attach($color);
        $response = $this->get('/filter?brand=' . $brand['id'] . '&color=1');
        $response->assertJsonCount(1, 'data');
        $response->assertStatus(200);
    }

    public function test_products_are_fetched_related_to_brands_when_color_and_size_is_given()
    {
        $user = factory(\App\Models\User::class)->create();
        $brand = factory(\App\Models\Brand::class)->create();
        $size = factory(\App\Models\Size::class)->create(['name' => 'ExtraSmall']);
        factory(\App\Models\Product::class, 2)->create(['user_id' => $user['id'], 'status' => 'published', 'brand_id' => $brand['id'], 'price' => 200]);
        $product = factory(\App\Models\Product::class)->create(['user_id' => $user['id'], 'status' => 'published', 'brand_id' => $brand['id'], 'size_id' => $size['id'], 'price' => 100]);
        $color = factory(\App\Models\Color::class)->create(['name' => 'red', 'value' => 'red']);
        $product->colors()->attach($color);
        $response = $this->get('/filter?brand=' . $brand['id'] . '&color=1&size=' . $size['id']);
        $response->assertJsonCount(1, 'data');
        $response->assertStatus(200);
    }

    public function test_products_are_fetched_related_to_brands_when_price_range_is_given()
    {

        $user = factory(\App\Models\User::class)->create();
        $brand = factory(\App\Models\Brand::class)->create();
        factory(\App\Models\Product::class, 2)->create(['user_id' => $user['id'], 'status' => 'published', 'brand_id' => $brand['id'], 'price' => 200]);
        factory(\App\Models\Product::class)->create(['user_id' => $user['id'], 'status' => 'published', 'brand_id' => $brand['id'], 'price' => 200]);
        $response = $this->get('/filter?brand=' . $brand['id'] . '&price_min=10&price_max=2000');
        $response->assertJsonCount(3, 'data');
        $response->assertStatus(200);
    }

    public function test_products_are_fetched_related_to_brands_and_categories_when_size_is_given()
    {
        $user = factory(\App\Models\User::class)->create();
        $brand = factory(\App\Models\Brand::class)->create();
        $category = factory(\App\Models\Category::class)->create();
        $size = factory(\App\Models\Size::class)->create(['name' => 'ExtraSmall']);
        factory(\App\Models\Product::class, 2)->create(['user_id' => $user['id'], 'status' => 'published', 'brand_id' => $brand['id'], 'price' => 200]);
        $product = factory(\App\Models\Product::class)->create(['user_id' => $user['id'], 'status' => 'published', 'brand_id' => $brand['id'], 'size_id' => $size['id'], 'price' => 200]);
        $category->products()->attach($product);
        $response = $this->get('/filter?brand=' . $brand['id'] . '&category=' . $category['id'] . '&size=' . $size['id']);
        $response->assertJsonCount(1, 'data');
        $response->assertStatus(200);

    }

    public function test_products_are_fetched_when_size_is_given()
    {
        $user = factory(\App\Models\User::class)->create();
        $size = factory(\App\Models\Size::class)->create(['name' => 'ExtraSmall']);
        $brand = factory(\App\Models\Brand::class)->create();
        factory(\App\Models\Product::class, 2)->create(['user_id' => $user['id'], 'status' => 'published', 'size_id' => $size['id'], 'price' => 200, 'brand_id' => $brand['id']]);
        factory(\App\Models\Product::class)->create(['user_id' => $user['id'], 'status' => 'published', 'price' => 200, 'size_id' => 101]);
        $response = $this->get('/filter?size=' . $size['id']);
        $response->assertJsonCount(2, 'data');
        $response->assertStatus(200);

    }

    public function test_products_are_fetched_when_multiple_sizes_are_given()
    {
        $user = factory(\App\Models\User::class)->create();
        $size1 = factory(\App\Models\Size::class)->create(['name' => 'ExtraSmall']);
        $size2 = factory(\App\Models\Size::class)->create(['name' => 'ExtraLarge']);
        $brand = factory(\App\Models\Brand::class)->create();
        factory(\App\Models\Product::class, 2)->create(['user_id' => $user['id'], 'status' => 'published', 'size_id' => $size1['id'], 'price' => 200, 'brand_id' => $brand['id']]);
        factory(\App\Models\Product::class)->create(['user_id' => $user['id'], 'status' => 'published', 'size_id' => $size2['id'], 'price' => 100, 'brand_id' => $brand['id']]);
        $response = $this->get('/filter?size=' . $size1['id'] . ',' . $size2['id']);
        $response->assertJsonCount(3, 'data');
        $response->assertStatus(200);
    }

    public function test_products_are_fetched_when_colors_are_given()
    {
        $user = factory(\App\Models\User::class)->create();
        $brand = factory(\App\Models\Brand::class)->create();
        factory(\App\Models\Product::class, 2)->create(['user_id' => $user['id'], 'status' => 'published', 'price' => 200, 'brand_id' => $brand['id']]);
        $product = factory(\App\Models\Product::class)->create(['user_id' => $user['id'], 'status' => 'published', 'price' => 100, 'brand_id' => $brand['id']]);
        $color = factory(\App\Models\Color::class)->create(['name' => 'red', 'value' => 'red']);
        $product->colors()->attach($color);
        $response = $this->get('/filter?color=1');
        $response->assertJsonCount(1, 'data');
        $response->assertStatus(200);

    }

    public function test_products_are_fetched_when_different_colors_are_given()
    {
        $user = factory(\App\Models\User::class)->create();
        $brand = factory(\App\Models\Brand::class)->create();
        $product1 = factory(\App\Models\Product::class)->create(['user_id' => $user['id'], 'status' => 'published', 'price' => 200, 'brand_id' => $brand['id']]);
        $product2 = factory(\App\Models\Product::class)->create(['user_id' => $user['id'], 'status' => 'published', 'price' => 200, 'brand_id' => $brand['id']]);
        $color1 = factory(\App\Models\Color::class)->create(['name' => 'red', 'value' => 'red']);
        $color2 = factory(\App\Models\Color::class)->create(['name' => 'aqua', 'value' => 'aqua']);
        $product1->colors()->attach($color1);
        $product2->colors()->attach($color2);
        $response = $this->get('/filter?color=1,2');
        $response->assertJsonCount(2, 'data');
        $response->assertStatus(200);

    }

    public function test_products_are_fetched_when_different_colors_and_sizes_are_given()
    {
        $user = factory(\App\Models\User::class)->create();
        $brand = factory(\App\Models\Brand::class)->create();
        $size1 = factory(\App\Models\Size::class)->create(['name' => 'ExtraSmall']);
        $size2 = factory(\App\Models\Size::class)->create(['name' => 'ExtraLarge']);
        $product1 = factory(\App\Models\Product::class)->create(['user_id' => $user['id'], 'status' => 'published', 'size_id' => $size1['id'], 'price' => 200, 'brand_id' => $brand['id']]);
        $product2 = factory(\App\Models\Product::class)->create(['user_id' => $user['id'], 'status' => 'published', 'size_id' => $size2['id'], 'price' => 100, 'brand_id' => $brand['id']]);
        $color1 = factory(\App\Models\Color::class)->create(['name' => 'red', 'value' => 'red']);
        $color2 = factory(\App\Models\Color::class)->create(['name' => 'aqua', 'value' => 'aqua']);
        $product1->colors()->attach($color1);
        $product2->colors()->attach($color2);
        $response = $this->get('/filter?color=1,2&size=' . $size1['id'] . ',' . $size2['id']);
        $response->assertJsonCount(2, 'data');
        $response->assertStatus(200);
    }


    public function test_products_are_fetched_when_multiple_colors_and_sizes_are_given_with_price_ranges()
    {
        $user = factory(\App\Models\User::class)->create();
        $brand = factory(\App\Models\Brand::class)->create();
        $size1 = factory(\App\Models\Size::class)->create(['name' => 'ExtraSmall']);
        $size2 = factory(\App\Models\Size::class)->create(['name' => 'ExtraLarge']);
        factory(\App\Models\Product::class, 2)->create(['user_id' => $user['id'], 'status' => 'published', 'size_id' => $size1['id'], 'price' => 200, 'brand_id' => $brand['id']]);
        $product = factory(\App\Models\Product::class)->create(['user_id' => $user['id'], 'status' => 'published', 'size_id' => $size2['id'], 'price' => 99, 'brand_id' => $brand['id']]);
        $color1 = factory(\App\Models\Color::class)->create(['name' => 'red', 'value' => 'red']);
        $product->colors()->attach($color1);
        $response = $this->get('/filter?price_min=10&price_max=100&color=1&size=' . $size2['id']);
        $response->assertJsonCount(1, 'data');
        $response->assertStatus(200);
    }

    public function test_products_are_fetched_with_multiple_color_when_price_range_is_given()
    {
        $user = factory(\App\Models\User::class)->create();
        $brand = factory(\App\Models\Brand::class)->create();
        factory(\App\Models\Product::class, 2)->create(['user_id' => $user['id'], 'status' => 'published', 'price' => 200, 'brand_id' => $brand['id']]);
        $product = factory(\App\Models\Product::class)->create(['user_id' => $user['id'], 'status' => 'published', 'price' => 100, 'brand_id' => $brand['id']]);
        $color1 = factory(\App\Models\Color::class)->create(['name' => 'red', 'value' => 'red']);
        $product->colors()->attach($color1);
        $response = $this->get('/filter?price_min=10&price_max=100&color=1');
        $response->assertJsonCount(1, 'data');
        $response->assertStatus(200);
    }

    public function test_products_are_fetched_related_to_categories_when_color_is_given()
    {
        $user = factory(\App\Models\User::class)->create();
        $brand = factory(\App\Models\Brand::class)->create();
        $category = factory(\App\Models\Category::class)->create();
        $product1 = factory(\App\Models\Product::class)->create(['user_id' => $user['id'], 'status' => 'published', 'price' => 200, 'brand_id' => $brand['id']]);
        $product2 = factory(\App\Models\Product::class)->create(['user_id' => $user['id'], 'status' => 'published', 'price' => 200, 'brand_id' => $brand['id']]);
        $category->products()->attach($product1);
        $category->products()->attach($product2);
        $color1 = factory(\App\Models\Color::class)->create(['name' => 'red', 'value' => 'red']);
        $product1->colors()->attach($color1);
        $response = $this->get('/filter?category=' . $category['id'] . '&color=1');
        $response->assertJsonCount(1, 'data');
        $response->assertStatus(200);
    }

    public function test_products_are_fetched_of_categories_and_brands_when_size_is_given()
    {
        $user = factory(\App\Models\User::class)->create();
        $size = factory(\App\Models\Size::class)->create(['name' => 'ExtraSmall']);
        $category = factory(\App\Models\Category::class)->create();
        $brand = factory(\App\Models\Brand::class)->create(['name' => 'nike']);
        $product1 = factory(\App\Models\Product::class)->create(['user_id' => $user['id'], 'status' => 'published', 'size_id' => 100, 'price' => 200, 'brand_id' => $brand['id']]);
        $product2 = factory(\App\Models\Product::class)->create(['user_id' => $user['id'], 'status' => 'published', 'size_id' => $size['id'], 'price' => 200, 'brand_id' => $brand['id']]);
        $category->products()->attach($product1);
        $category->products()->attach($product2);
        $response = $this->get('/filter?category=' . $category['id'] . '&brand=' . $brand['id'] . '&size=' . $size['id']);
        $response->assertJsonCount(1, 'data');
        $response->assertStatus(200);
    }

    public function test_products_are_also_fetched_when_price_min_is_given()
    {
        $user = factory(\App\Models\User::class)->create();
        $brand = factory(\App\Models\Brand::class)->create();
        factory(\App\Models\Product::class, 2)->create(['user_id' => $user['id'], 'status' => 'published', 'price' => 400, 'brand_id' => $brand['id']]);
        factory(\App\Models\Product::class, 4)->create(['user_id' => $user['id'], 'status' => 'published', 'price' => 500, 'brand_id' => $brand['id']]);
        $response = $this->get('/filter?price_min=300');
        $response->assertJsonCount(6, 'data');
        $response->assertStatus(200);
    }

    public function test_products_are_also_fetched_when_price_max_is_given()
    {
        $user = factory(\App\Models\User::class)->create();
        $brand = factory(\App\Models\Brand::class)->create();
        factory(\App\Models\Product::class, 2)->create(['user_id' => $user['id'], 'status' => 'published', 'price' => 400, 'brand_id' => $brand['id']]);
        factory(\App\Models\Product::class, 4)->create(['user_id' => $user['id'], 'status' => 'published', 'price' => 500, 'brand_id' => $brand['id']]);
        $response = $this->get('/filter?price_max=500');
        $response->assertJsonCount(6, 'data');
        $response->assertStatus(200);
    }

    public function test_only_published_products_are_filter_on_each_request()
    {
        $user = factory(\App\Models\User::class)->create();
        $brand = factory(\App\Models\Brand::class)->create();
        factory(\App\Models\Product::class, 2)->create(['user_id' => $user['id'], 'status' => 'published', 'price' => 400, 'brand_id' => $brand['id']]);
        factory(\App\Models\Product::class, 4)->create(['user_id' => $user['id'], 'status' => 'drafted', 'price' => 500, 'brand_id' => $brand['id']]);
        $response = $this->get('/filter?price_max=500');
        $response->assertJsonCount(2, 'data');
        $response->assertStatus(200);
    }
}

