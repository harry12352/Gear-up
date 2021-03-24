<?php

namespace Tests\Feature\Shipping\Usps;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Services\Shipping\Usps\Client;
use Illuminate\Foundation\Testing\WithFaker;

class ClientTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function buildClient()
    {
        $uspsConfig = config('services.usps');

        $uspsClient = new Client($uspsConfig['host'], $uspsConfig['client_id']);

        $this->assertInstanceOf(Client::class, $uspsClient);
    }

    /** @test */
    public function getPackageRates()
    {
        $faker = $this->faker();
        $fakeXml = '
        <?xml version="1.0" encoding="UTF-8"?>
        <RateV4Response>
            <Package ID="2">
                <ZipOrigination>'. $faker->randomNumber() .'</ZipOrigination>
                <ZipDestination>'. $faker->randomNumber() .'</ZipDestination>
                <Pounds>'. $faker->randomNumber() .'</Pounds>
                <Ounces>'. $faker->randomNumber() .'</Ounces>
                <Container>'. $faker->word() .'</Container>
                <Zone>'. $faker->randomDigit() .'</Zone>
                <Postage CLASSID="1">
                    <MailService>'. $faker->text() .'</MailService>
                    <Rate>'. $faker->randomFloat() .'</Rate>
                </Postage>
            </Package>
        </RateV4Response>
        ';
        Http::fake([
            'production.shippingapis.com/*' => Http::response(trim(str_replace("\n", "", $fakeXml)), 200)
        ]);
        $uspsConfig = config('services.usps');
        $package = [
            '_attributes' => ['ID' => '1'],
            'Service' => 'PRIORITY',
            'ZipOrigination' => '90001',
            'ZipDestination' => '10001',
            'Pounds' => '2.0',
            'Ounces' => '2',
            'Container' => 'VARIABLE',
            'Machinable' => 'false',
        ];

        $uspsClient = new Client($uspsConfig['host'], $uspsConfig['client_id']);
        $rates = $uspsClient->getRates($package);

        $this->assertArrayHasKey('Package', $rates);
    }
}
