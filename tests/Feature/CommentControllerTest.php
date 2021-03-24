<?php

namespace Tests\Feature;

use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_not_comment_on_an_drafted_product()
    {
        $user = factory(\App\Models\User::class)->create();
        $product = factory(\App\Models\Product::class)->create(['status' => 'drafted']);
        $content = 'nice product';
        $response = $this->actingAs($user)->post('/comment/product/' . $product->id, ['content' => $content]);
        $this->assertEquals('Comment creation failed', $response['message']);
        $response->assertStatus(403);
    }


    public function test_a_user_can_comment_on_a_product()
    {
        $user = factory(\App\Models\User::class)->create();
        $product = factory(\App\Models\Product::class)->create(['status' => 'published']);
        $content = 'nice product';
        $response = $this->actingAs($user)->post('/comment/product/' . $product->id, ['content' => $content]);
        $this->assertEquals('Comment creation successfull', $response['message']);
        $response->assertStatus(200);
    }

    public function test_a_user_comment_can_not_be_blank()
    {
        $user = factory(\App\Models\User::class)->create();
        $product = factory(\App\Models\Product::class)->create(['status' => 'published']);
        $content = '';
        $response = $this->actingAs($user)->post('/comment/product/' . $product->id, ['content' => $content]);
        $response->assertStatus(302);
    }

    public function test_a_user_can_delete_his_comment()
    {
        $this->withoutExceptionHandling();
        $user = factory(\App\Models\User::class)->create();
        $product = factory(\App\Models\Product::class)->create(['status' => 'published']);
        $content = 'good one';
        $this->actingAs($user)->post('/comment/product/' . $product->id, ['content' => $content]);
        $comment = Comment::first();
        $response = $this->actingAs($user)->get('/comment/' . $comment['id'] . '/delete');
        $this->assertEquals('Comment deletion successfull', $response['message']);
        $response->assertStatus(200);
    }

    public function test_a_user_can_not_delete_someone_else_comment()
    {
        $this->withoutExceptionHandling();
        $user1 = factory(\App\Models\User::class)->create();
        $user2 = factory(\App\Models\User::class)->create();
        $product = factory(\App\Models\Product::class)->create(['status' => 'published']);
        $content = 'good one';
        $this->actingAs($user1)->post('/comment/product/' . $product->id, ['content' => $content]);
        $comment = Comment::first();
        $response = $this->actingAs($user2)->get('/comment/' . $comment['id'] . '/delete');
        $this->assertEquals('You can not perform this action', $response['message']);
        $response->assertStatus(403);
    }

}
