<?php

namespace Tests\Feature;

use App\Features\Payment\Payment;
use App\Models\Product;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function buyProductSuccess()
    {
        $user = factory(User::class)->create();
        $product = factory(Product::class)->create();
        $orderId = $this->faker->iban;
        $this->partialMock(Payment::class, function ($mock) use ($orderId) {
            $mock->shouldReceive('createOrder')->andReturn($orderId);
        });

        $response = $this->actingAs($user)->get("/{$user->username}/product/buy/{$product->slug}");

        $this->assertTrue(is_int(strpos($response->getTargetUrl(), "paypal")));
        $response->assertRedirect();
    }

    /** @test */
    public function acceptPayment()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $orderId = $this->faker->iban;
        $productId = $this->faker->randomNumber(4);
        $fakeOrderApproval = [
            'transaction_id' => $this->faker->randomNumber(4),
            'status' => $this->faker->word,
            'orderId' => $orderId,
            'full_transaction' => (array) $this->faker->creditCardDetails()
        ];
        $this->partialMock(Payment::class, function ($mock) use ($fakeOrderApproval) {
            $mock->shouldReceive('approveOrder')->andReturn($fakeOrderApproval);
        });

        $response = $this->actingAs($user)->withSession([
            'orderId' => $orderId,
            'productId' => $productId,
        ])->get('/accept-payment');

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'order_id' => $fakeOrderApproval['orderId'],
            'transaction_id' => $fakeOrderApproval['transaction_id']
        ]);
    }
}
