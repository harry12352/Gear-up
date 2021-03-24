<?php

namespace Tests\Feature\Shipping\Usps;

use App\Services\Shipping\Package;
use App\Services\Shipping\Usps\Shipment;
use App\Services\Shipping\Usps\Client;
use App\Services\Shipping\Usps\Postage;
use App\Services\Shipping\Usps\Usps;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UspsTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function getShipmentRates()
    {
        $this->markTestSkipped('Must be revisited, when implementing USPS');
        $fakeShipmentRates = [
            'Package' => [
                'Postage' => [
                    'MailService' => $this->faker->word(),
                    'Rate' => $this->faker->randomFloat(),
                ],
            ],
        ];
        /** @var Client */
        $clientMock = $this->partialMock(Client::class, function ($mock) use ($fakeShipmentRates) {
            $mock->shouldReceive('getRates')->andReturn($fakeShipmentRates);
        });
        /** @var Usps */
        $usps = new Usps($clientMock);

        $origin = 90001;
        $desination = 10001;
        $service = "PRIORITY";
        $shipment = new Shipment($origin, $desination, $service);
        $shipment->addPackage(new Package(2));

        $postage = $usps->getRate($shipment);

        $this->assertInstanceOf(Postage::class, $postage);
        $this->assertEquals($fakeShipmentRates['Package']['Postage']['Rate'], $postage->getRate());
    }
}
