<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class ProductControllerTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    public function test_a_logged_in_user_can_view_its_product_creation_form()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->get('/profile/' . $user->username . '/products/create');
        $response->assertViewIs('profile.products.create');
    }

    public function test_a_logged_in_user_can_not_view_others_product_creation_form()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $response = $this->actingAs($user1)->get('/profile/' . $user2->username . '/products/create');
        $response->assertSessionHas('error');
    }

    public function test_a_user_not_logged_in_can_not_view_product_creation_form()
    {
        $user = factory(User::class)->create();
        $response = $this->get('/profile/' . $user->username . '/products/create');
        $response->assertSessionHas('error');
    }

    public function test_a_user_not_registered_can_not_view_product_creation_form()
    {
        $response = $this->get('/profile/random/products/create');
        $response->assertStatus(404);
    }

    public function test_a_logged_in_user_can_create_its_product()
    {

        $user = factory(User::class)->create();
        $brand = factory(Brand::class)->create();
        $category = factory(Category::class)->create();
        $color = factory(\App\Models\Color::class)->create(['name' => 'red', 'value' => 'red']);
        $size = factory(\App\Models\Size::class)->create();
        $payload = ['title' => 'new',
            'size' => $size['id'],
            'color' => [$color['id']], 'price' => '4', 'description' => 'dumpy description', 'status' => 'published', 'category' => [$category->id], 'brand' => $brand->id];
        $response = $this->actingAs($user)->post('/profile/' . $user->username . '/products/store', $payload);
        $productSlug = Product::first()->slug;
        $response->assertRedirect(route('products.edit', ['user' => $user->username, 'product' => $productSlug]));

    }

    public function test_a_not_logged_in_user_can_not_create_its_product()
    {
        $user = factory(User::class)->create();
        $response = $this->post('/profile/' . $user->username . '/products/store');
        $response->assertSessionHas('error');
    }

    public function test_a_logged_in_user_can_not_create_product_for_other_users()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $response = $this->actingAs($user1)->post('/profile/' . $user2->username . '/products/store');
        $response->assertSessionHas('error');
    }

    public function test_a_user_not_registered_can_not_create_product()
    {
        $response = $this->post('/profile/random/products/store');
        $response->assertStatus(404);

    }

    public function test_that_required_fields_for_product_shows_errors_upon_lefting_empty()
    {
        $user = factory(User::class)->create();
        $brand = factory(Brand::class)->create();
        $category = factory(Category::class)->create();
        $size = factory(\App\Models\Size::class)->create();
        $payload = ['title' => '',
            'size' => $size['id'],
            'color' => 'black', 'price' => '4', 'description' => 'dumpy description', 'status' => 'published', 'category' => [$category->id], 'brand' => $brand->id];
        $response = $this->actingAs($user)->post('/profile/' . $user->username . '/products/store', $payload);
        $response->assertSessionHasErrors('title');
    }

    public function test_a_product_can_be_attached_to_mutiple_categories()
    {
        $user = factory(User::class)->create();
        $brand = factory(Brand::class)->create();
        $category1 = factory(Category::class)->create();
        $category2 = factory(Category::class)->create();
        $color = factory(\App\Models\Color::class)->create(['name' => 'red', 'value' => 'red']);
        $size = factory(\App\Models\Size::class)->create();
        $payload = ['title' => 'new',
            'size' => $size['id'],
            'color' => [$color['id']], 'price' => '4', 'description' => 'dumpy description', 'status' => 'published', 'category' => [$category1->id, $category2->id], 'brand' => $brand->id];
        $response = $this->actingAs($user)->post('/profile/' . $user->username . '/products/store', $payload);
        $productSlug = Product::first()->slug;
        $response->assertRedirect(route('products.edit', ['user' => $user->username, 'product' => $productSlug]));
    }

    public function test_a_logged_in_user_can_view_its_edit_product_form()
    {
        $user = factory(User::class)->create();
        factory(Product::class)->create(['status' => 'published', 'user_id' => $user->id]);
        $productSlug = Product::first()->slug;
        $response = $this->actingAs($user)->get('/profile/' . $user->username . '/products/edit/' . $productSlug);
        $response->assertViewIs('profile.products.edit');
    }

    public function test_a_not_logged_in_user_can_not_view_edit_product_form()
    {
        $user = factory(\App\Models\User::class)->create();
        factory(Product::class)->create(['status' => 'published']);
        $productSlug = Product::first()->slug;
        $response = $this->get('/profile/' . $user->username . '/products/edit/' . $productSlug);
        $response->assertStatus(302);
    }

    public function test_error_is_thrown_if_image_is_not_added_to_published_product()
    {
        $user = factory(User::class)->create();
        factory(Product::class)->create(['status' => 'published', 'user_id' => $user->id]);
        $color = factory(\App\Models\Color::class)->create(['name' => 'red', 'value' => 'red']);
        $productSlug = Product::first()->slug;
        $brand = factory(Brand::class)->create();
        $category = factory(Category::class)->create();
        $size = factory(\App\Models\Size::class)->create();
        $payload = ['title' => 'new1',
            'size' => $size['id'],
            'color' => [$color['id']], 'price' => '4', 'description' => 'dumpy description', 'status' => 'published', 'category' => [$category->id], 'brand' => $brand->id];
        $response = $this->actingAs($user)->post('/profile/' . $user->username . '/products/update/' . $productSlug, $payload);
        $response->assertSessionHasErrors('file');
    }

    public function test_a_product_having_drafted_status_is_updated_even_having_no_images_attached()
    {
        $user = factory(User::class)->create();
        $brand = factory(Brand::class)->create();
        factory(Category::class)->create();
        factory(Product::class)->create(['status' => 'published', 'user_id' => $user->id, 'brand_id' => $brand->id]);
        $productSlug = Product::first()->slug;
        $color = factory(\App\Models\Color::class)->create(['name' => 'red', 'value' => 'red']);
        $size = factory(\App\Models\Size::class)->create();
        $brand = factory(Brand::class)->create();
        $category = factory(Category::class)->create();
        $payload = ['title' => 'new1',
            'size' => $size['id'],
            'color' => [$color['id']], 'price' => '4', 'description' => 'dumpy description', 'status' => 'drafted', 'category' => [$category->id], 'brand' => $brand->id];
        $response = $this->actingAs($user)->post('/profile/' . $user->username . '/products/update/' . $productSlug, $payload);
        $response->assertRedirect('/');
    }

    public function test_a_product_is_updated_having_published_status_and_attached_images()
    {
        $user = factory(User::class)->create();
        $brand = factory(Brand::class)->create();
        factory(Product::class)->create(['status' => 'published', 'user_id' => $user->id, 'brand_id' => $brand->id]);
        $color = factory(\App\Models\Color::class)->create(['name' => 'red', 'value' => 'red']);
        $productId = Product::first()->id;
        $size = factory(\App\Models\Size::class)->create();
        $productSlug = Product::first()->slug;
        Storage::fake('public');
        $image = "picture.jpeg";
        $imagePath = "images/" . $image;
        $this->actingAs($user)->post(
            '/images/store', [
                'file' => UploadedFile::fake()->image($image),
                'product_id' => $productId,
            ]
        );
        Storage::disk("public")->assertExists($imagePath);
        $brand = factory(Brand::class)->create();
        $category = factory(Category::class)->create();
        $payload = ['title' => 'new1',
            'size' => $size['id'],
            'color' => [$color['id']], 'price' => '4', 'description' => 'dumpy description', 'status' => 'published', 'category' => [$category->id], 'brand' => $brand->id];
        $response = $this->actingAs($user)->post('/profile/' . $user->username . '/products/update/' . $productSlug, $payload);
        $response->assertRedirect('/');
    }

    public function test_a_logged_in_user_can_view_his_all_products()
    {
        $user = factory(User::class)->create();
        factory(Product::class)->create(['status' => 'published', 'user_id' => $user->id]);
        $productId = Product::first()->id;
        Storage::fake('public');
        $image = "picture.jpeg";
        $imagePath = "images/" . $image;
        $this->actingAs($user)->post(
            '/images/store', [
                'file' => UploadedFile::fake()->image($image),
                'product_id' => $productId,
            ]
        );
        Storage::disk("public")->assertExists($imagePath);
        $response = $this->actingAs($user)->get('/profile/' . $user->username . '/products');
        $response->assertViewIs('profile.products');
    }

    public function test_a_logged_in_user_can_view_others_published_products()
    {
        $user1 = factory(User::class)->create();
        factory(Product::class)->create(['status' => 'published', 'user_id' => $user1->id]);
        $productId = Product::first()->id;
        Storage::fake('public');
        $image = "picture.jpeg";
        $imagePath = "images/" . $image;
        $this->actingAs($user1)->post(
            '/images/store', [
                'file' => UploadedFile::fake()->image($image),
                'product_id' => $productId,
            ]
        );
        Storage::disk("public")->assertExists($imagePath);
        $user2 = factory(User::class)->create();
        $response = $this->actingAs($user2)->get('/profile/' . $user1->username . '/products');
        $response->assertViewIs('profile.products');
    }

    public function test_a_logged_in_user_can_view_particular_published_product_belonging_to_passed_username()
    {
        $user1 = factory(User::class)->create();
        $brand = factory(\App\Models\Brand::class)->create();
        $size = factory(\App\Models\Size::class)->create();
        factory(Product::class)->create(['status' => 'published', 'user_id' => $user1->id, 'brand_id' => $brand->id, 'size_id' => $size['id']]);
        $productId = Product::first()->id;
        $productSlug = Product::first()->slug;
        Storage::fake('public');
        $image = "picture.jpeg";
        $imagePath = "images/" . $image;
        $this->actingAs($user1)->post(
            '/images/store', [
                'file' => UploadedFile::fake()->image($image),
                'product_id' => $productId,
            ]
        );
        Storage::disk("public")->assertExists($imagePath);
        $user2 = factory(User::class)->create();
        $response = $this->actingAs($user2)->get('/profile/' . $user1->username . '/products/view/' . $productSlug);
        $response->assertViewIs('profile.products.show');
    }

    public function test_a_logged_in_user_can_not_view_particular_drafted_product_belonging_to_passed_username()
    {
        $user1 = factory(User::class)->create();
        factory(Product::class)->create(['status' => 'drafted', 'user_id' => $user1->id]);
        $productSlug = Product::first()->slug;
        $user2 = factory(User::class)->create();
        $response = $this->actingAs($user2)->get('/profile/' . $user1->username . '/products/view/' . $productSlug);
        $response->assertSessionHas('error');
    }
}
