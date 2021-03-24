<?php

namespace Tests\Feature;

use App\Models\Offer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OfferControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_make_an_offer_on_an_product()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $product = factory(\App\Models\Product::class)->create(['status' => 'published', 'user_id' => $user2['id']]);
        $response = $this->actingAs($user1)->post('/profile/' . $user1['username'] . '/products/' . $product['id'] . '/offer/new', ['offered_price' => 2]);
        $response->assertRedirect('/');
    }

    public function test_a_user_can_not_make_an_offer_on_his_own_product()
    {
        $user = factory(User::class)->create();
        $product = factory(\App\Models\Product::class)->create(['status' => 'published', 'user_id' => $user['id']]);
        $response = $this->actingAs($user)->post('/profile/' . $user['username'] . '/products/' . $product['id'] . '/offer/new', ['offered_price' => 2]);
        $response->assertRedirect('/');
        $response->assertSessionHas('error');
    }

    public function test_a_user_can_delete_its_pending_offer()
    {
        $user = factory(User::class)->create();
        $product = factory(\App\Models\Product::class)->create(['status' => 'published']);
        $offer = factory(\App\Models\Offer::class)->create(['status' => 'pending', 'user_id' => $user['id'], 'product_id' => $product['id']]);
        $response = $this->actingAs($user)->get('/profile/' . $user['username'] . '/products/' . $product['id'] . '/offer/' . $offer['id'] . '/delete');
        $this->assertEquals('You have successfully deleted offer on this product', $response['message']);
        $response->assertStatus(200);
    }

    public function test_a_user_can_not_delete_its_declined_offer()
    {
        $user = factory(User::class)->create();
        $product = factory(\App\Models\Product::class)->create(['status' => 'published']);
        $offer = factory(\App\Models\Offer::class)->create(['status' => 'declined', 'user_id' => $user['id'], 'product_id' => $product['id']]);
        $response = $this->actingAs($user)->get('/profile/' . $user['username'] . '/products/' . $product['id'] . '/offer/' . $offer['id'] . '/delete');
        $this->assertEquals('You have not access to perform this action', $response['message']);
        $response->assertStatus(401);
    }

    public function test_a_product_owner_can_accept_a_offer_on_his_product()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $product = factory(\App\Models\Product::class)->create(['status' => 'published', 'user_id' => $user1['id']]);
        $offer = factory(\App\Models\Offer::class)->create(['status' => 'pending', 'user_id' => $user2['id'], 'product_id' => $product['id']]);
        $response = $this->actingAs($user1)->get('/profile/' . $user1['username'] . '/products/' . $product['id'] . '/offer/' . $offer['id'] . '/accept');
        $offer = Offer::first();
        $this->assertEquals('accepted', $offer['status']);
        $response->assertSessionHas('success');
    }

    public function test_a_product_owner_can_decline_a_offer_on_his_product()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $product = factory(\App\Models\Product::class)->create(['status' => 'published', 'user_id' => $user1['id']]);
        $offer = factory(\App\Models\Offer::class)->create(['status' => 'pending', 'user_id' => $user2['id'], 'product_id' => $product['id']]);
        $response = $this->actingAs($user1)->get('/profile/' . $user1['username'] . '/products/' . $product['id'] . '/offer/' . $offer['id'] . '/decline');
        $offer = Offer::first();
        $this->assertEquals('declined', $offer['status']);
        $response->assertSessionHas('success');
    }

    public function test_a_page_shows_all_offers_related_to_a_particular_user()
    {
        $user1 = factory(\App\Models\User::class)->create();
        $user2 = factory(\App\Models\User::class)->create();
        $product = factory(\App\Models\Product::class)->create(['status' => 'published', 'user_id' => $user2['id']]);
        factory(\App\Models\Offer::class, 3)->create(['user_id' => $user1['id'], 'status' => 'pending', 'product_id' => $product['id']]);
        $response = $this->actingAs($user1)->get('/profile/' . $user1['username'] . '/offers');
        $response->assertViewIs('profile.offers.index');
    }

    public function test_a_user_can_not_see_others_offers()
    {
        $user1 = factory(\App\Models\User::class)->create();
        $user2 = factory(\App\Models\User::class)->create();
        $product = factory(\App\Models\Product::class)->create(['status' => 'published', 'user_id' => $user2['id']]);
        factory(\App\Models\Offer::class, 3)->create(['user_id' => $user1['id'], 'status' => 'pending', 'product_id' => $product['id']]);
        $response = $this->actingAs($user2)->get('/profile/' . $user1['username'] . '/offers');
        $response->assertSessionHas('error');
    }

}
