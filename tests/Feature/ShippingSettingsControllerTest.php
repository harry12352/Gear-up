<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Services\Shipping\Fedex\Fedex;

class ShippingSettingsControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function updateShippingSettings()
    {
        $this->partialMock(Fedex::class, function ($mock) {
            $mock->shouldReceive('validateShipping')->andReturn(['State' => 'standardized']);
        });
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        
        // Going to settings page so that we get redirected back to correct page.
        $response = $this->actingAs($user)->get(route('settings.shipping'));

        $address = $this->faker->address;
        $zipcode = $this->faker->postcode;
        $state = $this->faker->state;
        $payload = [
            'address' => $address,
            'address_2' => $this->faker->streetAddress,
            'zip_code' => $zipcode,
            'city' => $this->faker->city,
            'state' => $state,
            'country' => $this->faker->country
        ];

        $response = $this->actingAs($user)->post(route("settings.shipping"), $payload);
        $response->assertRedirect(route("settings.shipping"))
            ->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('shipping_information', [
            "address" => $address,
            "zip_code" => $zipcode,
            "state" => $state,
        ]);
    }

    /** @test */
    public function validateShippingAddressFailure()
    {
        $this->partialMock(Fedex::class, function ($mock) {
            $mock->shouldReceive('validateShipping')->andReturn(['State' => 'unkown']);
        });
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        
        // Going to settings page so that we get redirected back to correct page.
        $response = $this->actingAs($user)->get(route('settings.shipping'));

        $address = $this->faker->address;
        $zipcode = $this->faker->postcode;
        $state = $this->faker->state;
        $payload = [
            'address' => $address,
            'address_2' => $this->faker->streetAddress,
            'zip_code' => $zipcode,
            'city' => $this->faker->city,
            'state' => $state,
            'country' => $this->faker->country
        ];

        $response = $this->actingAs($user)->post(route("settings.shipping"), $payload);
        $response->assertRedirect(route("settings.shipping"))
            ->assertSessionHas('error');
    }
}
