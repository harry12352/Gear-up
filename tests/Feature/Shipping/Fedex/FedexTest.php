<?php

namespace Tests\Feature\Shipping\Fedex;

use Tests\TestCase;
use App\Services\Shipping\Fedex\Fedex;
use App\Services\Shipping\Usps\Shipment;
use App\Services\Shipping\Fedex\Services\RateService;
use App\Services\Shipping\Fedex\Services\AddressValidationService;
use FedEx\AddressValidationService\ComplexType\AddressValidationReply;
use Mockery;

class FedexTest extends TestCase
{
    public function fakeRatesReply()
    {
        $array = ['RateReplyDetails' => [['RatedShipmentDetails' => [['ShipmentRateDetail' => ['RateType' => 'PAYOR_ACCOUNT_PACKAGE', 'TotalNetCharge' => ['Amount' => '232.232']]]], 'ServiceType' => 'FIRST_OVERNIGHT']]];
        return json_decode(json_encode($array));
    }


    public function fakeAddressReply()
    {
        $fakeAddressValidationReply = Mockery::mock('AddressValidationReply');
        $fakeAddressValidationReply->shouldReceive('toArray')->andReturn(true);
        $fakeAddressValidationReply->HighestSeverity = "SUCCESS";
        $fakeAddressValidationReply->AddressResults = [$fakeAddressValidationReply];
        return $fakeAddressValidationReply;
    }

    public function fakeShippmentReply()
    {
        $array = ['HighestSeverity' => 'SUCCESS'];
        return json_decode(json_encode($array));
    }


    public function test_rates_are_fetched()
    {
        $this->partialMock(RateService::class, function ($mock) {
            $mock->shouldReceive('authenticate')->andReturn(true);
            $mock->shouldReceive('setVersion')->andReturn(true);
            $mock->shouldReceive('setShipper')->andReturn(true);
            $mock->shouldReceive('setRecipient')->andReturn(true);
            $mock->shouldReceive('setRateRequestTypes')->andReturn(true);
            $mock->shouldReceive('setPackages')->andReturn(true);
        });
        $this->partialMock(\FedEx\RateService\Request::class, function ($mock) {
            $mock->shouldReceive('getGetRatesReply')->andReturn($this->fakeRatesReply());
        });
        $origin = [
            'prefered_currency' => 'USD',
            'street_lines' => '10 Fed Ex Pkwy',
            'city' => 'Memphis',
            'state_or_province' => 'Tn',
            'postal_code' => 38115,
            'country_code' => 'US',
        ];
        $destination = [
            'prefered_currency' => 'USD',
            'street_lines' => '13450 Farmcrest Ct',
            'city' => 'Herdon',
            'state_or_province' => 'VA',
            'postal_code' => 20171,
            'country_code' => 'US',
        ];
        $package1Attrs = [
            'weight_count' => 12,
            'dimension_length' => 10,
            'dimension_width' => 10,
            'dimension_height' => 3,
            'group_package_count' => 1
        ];
        $package2Attrs = [
            'weight_count' => 12,
            'dimension_length' => 10,
            'dimension_width' => 10,
            'dimension_height' => 3,
            'group_package_count' => 1
        ];
        $shipment = new  Shipment($origin, $destination);
        $shipment->addPackage($package1Attrs);
        $shipment->addPackage($package2Attrs);
        $fedexMock = $this->partialMock(Fedex::class, function ($mock) {
            $mock->shouldReceive('setEnvironment')->andReturn(true);
            $mock->shouldReceive('validateAddress')->andReturn(['error' => false]);
        });
        $rates = $fedexMock->getRates($shipment);
        $count = count($rates);
        $this->assertEquals(1, $count);

    }

    public function test_address_is_validated()
    {
        $this->partialMock(AddressValidationService::class, function ($mock) {
            $mock->shouldReceive('authenticate')->andReturn(true);
            $mock->shouldReceive('setVersion')->andReturn(true);
            $mock->shouldReceive('addressToValidate')->andReturn(true);
            $mock->shouldReceive('addressToValidate')->andReturn(true);
        });
        $this->partialMock(\FedEx\AddressValidationService\Request::class, function ($mock) {
            $mock->shouldReceive('getAddressValidationReply')->andReturn($this->fakeAddressReply());
        });
        $fedexMock = $this->partialMock(Fedex::class, function ($mock) {
            $mock->shouldReceive('setEnvironment')->andReturn(true);
        });
        $address = [
            'prefered_currency' => 'USD',
            'street_lines' => '13450 Farmcrest Ct',
            'city' => 'Herdon',
            'state_or_province' => 'VA',
            'postal_code' => 20171,
            'country_code' => 'US',
        ];
        $validation = $fedexMock->validateAddress($address);
        
        $this->assertTrue($validation);
    }

    public function test_ship_service_is_implemented()
    {
        $this->partialMock(AddressValidationService::class, function ($mock) {
            $mock->shouldReceive('authenticate')->andReturn(true);
            $mock->shouldReceive('setVersion')->andReturn(true);
            $mock->shouldReceive('setShipper')->andReturn(true);
            $mock->shouldReceive('setRecipient')->andReturn(true);
            $mock->shouldReceive('setSpecifications')->andReturn(true);
            $mock->shouldReceive('setPackage')->andReturn(true);
            $mock->shouldReceive('setPayor')->andReturn(true);
            $mock->shouldReceive('setPayment')->andReturn(true);
            $mock->shouldReceive('requestShipment')->andReturn(true);
            $mock->shouldReceive('processShipment')->andReturn(true);
        });
        $this->partialMock(\FedEx\ShipService\Request::class, function ($mock) {
            $mock->shouldReceive('getProcessShipmentReply')->andReturn($this->fakeShippmentReply());
        });
        $fedexMock = $this->partialMock(Fedex::class, function ($mock) {
            $mock->shouldReceive('setEnvironment')->andReturn(true);
        });
        $origin = [
            'prefered_currency' => 'USD',
            'street_lines' => '10 Fed Ex Pkwy',
            'city' => 'Memphis',
            'state_or_province' => 'Tn',
            'postal_code' => 38115,
            'country_code' => 'US',
        ];
        $destination = [
            'prefered_currency' => 'USD',
            'street_lines' => '13450 Farmcrest Ct',
            'city' => 'Herdon',
            'state_or_province' => 'VA',
            'postal_code' => 20171,
            'country_code' => 'US',
        ];
        $package1Attrs = [
            'weight_count' => 12,
            'dimension_length' => 10,
            'dimension_width' => 10,
            'dimension_height' => 3,
            'group_package_count' => 1
        ];
        $package2Attrs = [
            'weight_count' => 12,
            'dimension_length' => 10,
            'dimension_width' => 10,
            'dimension_height' => 3,
            'group_package_count' => 1
        ];
        $shipment = new  Shipment($origin, $destination);
        $shipment->addPackage($package1Attrs);
        $shipment->addPackage($package2Attrs);
        $result = $fedexMock->shipment($shipment);
        $this->assertEquals('SUCCESS', $result->HighestSeverity);
    }
}
