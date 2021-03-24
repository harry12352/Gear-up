<?php

namespace App\Services\Shipping\Fedex\Services;

use FedEx\RateService\ComplexType;
use FedEx\RateService\SimpleType;

class RateService
{
    public function authenticate($rateRequest)
    {
        $rateRequest->WebAuthenticationDetail->UserCredential->Key = config('services.fedex.key');;
        $rateRequest->WebAuthenticationDetail->UserCredential->Password = config('services.fedex.pass');
        $rateRequest->ClientDetail->AccountNumber = config('services.fedex.accNo');
        $rateRequest->ClientDetail->MeterNumber = config('services.fedex.meterNo');
        $rateRequest->TransactionDetail->CustomerTransactionId = 'testing rate service request';
    }

    public function setVersion($rateRequest)
    {
        $rateRequest->Version->ServiceId = 'crs';
        $rateRequest->Version->Major = 24;
        $rateRequest->Version->Minor = 0;
        $rateRequest->Version->Intermediate = 0;
        $rateRequest->ReturnTransitAndCommit = true;
    }

    public function setShipper($rateRequest, $shipment)
    {
        $rateRequest->RequestedShipment->PreferredCurrency = $shipment->origin['prefered_currency'];
        $rateRequest->RequestedShipment->Shipper->Address->StreetLines = $shipment->origin['street_lines'];
        $rateRequest->RequestedShipment->Shipper->Address->City = $shipment->origin['city'];
        $rateRequest->RequestedShipment->Shipper->Address->StateOrProvinceCode = $shipment->origin['state_or_province'];
        $rateRequest->RequestedShipment->Shipper->Address->PostalCode = $shipment->origin['postal_code'];
        $rateRequest->RequestedShipment->Shipper->Address->CountryCode = $shipment->origin['country_code'];
    }

    public function setRecipient($rateRequest, $shipment)
    {
        $rateRequest->RequestedShipment->Recipient->Address->StreetLines = $shipment->destination['street_lines'];
        $rateRequest->RequestedShipment->Recipient->Address->City = $shipment->destination['city'];
        $rateRequest->RequestedShipment->Recipient->Address->StateOrProvinceCode = $shipment->destination['state_or_province'];
        $rateRequest->RequestedShipment->Recipient->Address->PostalCode = $shipment->destination['postal_code'];
        $rateRequest->RequestedShipment->Recipient->Address->CountryCode = $shipment->destination['country_code'];
        $rateRequest->RequestedShipment->ShippingChargesPayment->PaymentType = SimpleType\PaymentType::_SENDER;
    }

    public function setRateRequestTypes($rateRequest, $shipment)
    {
        $rateRequest->RequestedShipment->RateRequestTypes = [SimpleType\RateRequestType::_PREFERRED, SimpleType\RateRequestType::_LIST];
        $rateRequest->RequestedShipment->PackageCount = count($shipment->getPackages());
    }

    public function setPackages($rateRequest, $shipment)
    {
        $obj = new ComplexType\RequestedPackageLineItem();
        $packageCount = count($shipment->getPackages());
        $arr = [];
        for ($i = 0; $i < $packageCount; $i++) {
            array_push($arr, $obj);
        }
        $rateRequest->RequestedShipment->RequestedPackageLineItems = $arr;
        foreach ($shipment->getPackages() as $key => $package) {
            $rateRequest->RequestedShipment->RequestedPackageLineItems[$key]->Weight->Value = $package['weight_count'];
            $rateRequest->RequestedShipment->RequestedPackageLineItems[$key]->Weight->Units = SimpleType\WeightUnits::_LB;
            $rateRequest->RequestedShipment->RequestedPackageLineItems[$key]->Dimensions->Length = $package['dimension_length'];
            $rateRequest->RequestedShipment->RequestedPackageLineItems[$key]->Dimensions->Width = $package['dimension_width'];
            $rateRequest->RequestedShipment->RequestedPackageLineItems[$key]->Dimensions->Height = $package['dimension_height'];
            $rateRequest->RequestedShipment->RequestedPackageLineItems[$key]->Dimensions->Units = SimpleType\LinearUnits::_IN;
            $rateRequest->RequestedShipment->RequestedPackageLineItems[$key]->GroupPackageCount = $package['group_package_count'];
        }
    }

}
