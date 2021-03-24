<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductImageControllerTest extends TestCase
{
    use RefreshDatabase, withFaker;

    public function test_image_is_attached_to_product()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        factory(Product::class)->create(['status' => 'published', 'user_id' => $user->id]);
        $productId = Product::first()->id;
        Storage::fake('public');
        $image = "picture.jpeg";
        $imagePath = "images/" . $image;
        $response = $this->actingAs($user)->post(
            '/images/store', [
                'file' => UploadedFile::fake()->image($image),
                'product_id' => $productId,
            ]
        );
        Storage::disk("public")->assertExists($imagePath);
        $this->assertEquals('Image Created successfully', $response['message']);
        $response->assertStatus(200);
    }


    public function test_a_user_can_delete_his_own_product_image()
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
        $file = File::first()->id;
        $response = $this->actingAs($user)->get('/images/delete/' . $file);
        $this->assertEquals('Image deleted successfully', $response['message']);
        Storage::disk("public")->assertMissing($imagePath);
        $response->assertStatus(200);
    }

    public function test_an_image_other_than_product_resource_name_is_not_deleted()
    {
        $user = factory(User::class)->create();
        $file = factory(\App\Models\File::class)->create(['resource_name' => 'page']);
        $response = $this->actingAs($user)->get('/images/delete/' . $file['id']);
        $this->assertEquals('You can not perform this action', $response['message']);
        $response->assertStatus(403);
    }

    public function test_that_an_image_not_belonging_to_product_owner_is_not_deleted()
    {
        // TODO > Why this test is slow?
        $user = factory(User::class)->create();
        $product = factory(\App\Models\Product::class)->create(['status' => 'published', 'user_id' => 23]);
        $file = factory(\App\Models\File::class)->create(['resource_name' => 'product', 'resource_id' => $product['id']]);
        $response = $this->actingAs($user)->get('/images/delete/' . $file['id']);
        $this->assertEquals('You can not perform this action', $response['message']);
    }
}
