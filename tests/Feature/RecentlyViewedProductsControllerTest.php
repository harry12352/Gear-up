<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RecentlyViewedProductsControllerTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    public function test_recent_products_ids_are_stored_in_cookie()
    {
        $user = factory(User::class)->create();
        $brand = factory(Brand::class)->create();
        $category = factory(Category::class)->create();
        $product = factory(Product::class)->create(['status' => 'published', 'user_id' => $user->id, 'brand_id' => $brand->id]);
        $product->categories()->attach($category);;
        $productId = Product::first()->id;
        factory(User::class)->create();
        $response = $this->get('/add/recently-viewed-product/' . $productId);
        $response->assertCookie('PI', json_encode([$productId]));
    }

    public function test_when_cookie_array_is_greater_than_5_new_productsIds_are_added_and_previous_are_shifted_subsequently()
    {
        $this->withoutExceptionHandling();
        $productsIds = [6, 7, 8, 9, 10];
        $arrayJson = json_encode($productsIds);
        $time = time() + (10 * 365 * 24 * 60 * 60);
        $user = factory(User::class)->create();
        factory(Product::class)->create(['status' => 'published', 'user_id' => $user->id, 'brand_id' => $this->faker->randomDigitNotNull]);
        $productId = Product::first()->id;
        $response = $this->withCookie('PI', $arrayJson, $time)->get('add/recently-viewed-product/' . $productId);
        $response->assertCookie('PI', json_encode([$productId, 6, 7, 8, 9]));
    }

    public function test_products_are_retrieved_by_decoding_cookie()
    {
        $this->withoutExceptionHandling();
        $productsIds = [1, 2, 3];
        $arrayJson = json_encode($productsIds);
        $time = time() + (10 * 365 * 24 * 60 * 60);
        $user = factory(User::class)->create();
        factory(Product::class)->create(['status' => 'published', 'user_id' => $user->id, 'brand_id' => $this->faker->randomDigitNotNull]);
        factory(Product::class)->create(['status' => 'published', 'user_id' => $user->id, 'brand_id' => $this->faker->randomDigitNotNull]);
        factory(Product::class)->create(['status' => 'published', 'user_id' => $user->id, 'brand_id' => $this->faker->randomDigitNotNull]);
        $response = $this->withCookie('PI', $arrayJson, $time)->get('/retrieve/recently-viewed-products');
        $response->assertOk();
    }
}
