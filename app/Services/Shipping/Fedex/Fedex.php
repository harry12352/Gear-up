<?php

namespace App\Services\Shipping\Fedex;

use App\Services\Shipping\Usps\Shipment;
use FedEx\RateService\Request;
use FedEx\RateService\ComplexType\RateRequest;
use FedEx\AddressValidationService\ComplexType\AddressValidationRequest;
use App\Services\Shipping\Fedex\Services\AddressValidationService;
use App\Services\Shipping\Fedex\Services\RateService;
use App\Services\Shipping\Fedex\Services\ShipService;

class Fedex
{
    public function getRates(Shipment $shipment)
    {
        $rateRequest = new RateRequest();
        $rateService = resolve(RateService::class);
        $rateService->authenticate($rateRequest);
        $rateService->setVersion($rateRequest);
        $rateService->setShipper($rateRequest, $shipment);
        if ($this->validateAddress($shipment->destination)['error'] == false) {
            $rateService->setRecipient($rateRequest, $shipment);
        } else {
            return response()->json(['error' => true, 'message' => 'Recipient Address not validated']);
        }
        $rateService->setRateRequestTypes($rateRequest, $shipment);
        $rateService->setPackages($rateRequest, $shipment);
        $rateServiceRequest = resolve(Request::class);
        $this->setEnvironment($rateRequest);
        $rateReply = $rateServiceRequest->getGetRatesReply($rateRequest);

        $getRates = [];

        if (!empty($rateReply->RateReplyDetails)) {
            foreach ($rateReply->RateReplyDetails as $rateReplyDetail) {
                if (!empty($rateReplyDetail->RatedShipmentDetails)) {
                    foreach ($rateReplyDetail->RatedShipmentDetails as $ratedShipmentDetail) {
                        array_push($getRates, [
                            $rateReplyDetail->ServiceType => [$ratedShipmentDetail->ShipmentRateDetail->RateType => $ratedShipmentDetail->ShipmentRateDetail->TotalNetCharge->Amount]
                        ]);
                    }
                }
            }
            return $getRates;
        }
    }

    public function validateAddress(array $address)
    {
        $addressValidationRequest = new AddressValidationRequest();
        $addressValidationService = resolve(AddressValidationService::class);
        $addressValidationService->authenticate($addressValidationRequest);
        $addressValidationService->setVersion($addressValidationRequest);
        $addressValidationService->addressToValidate($addressValidationRequest, $address);
        $request = resolve(\FedEx\AddressValidationService\Request::class);
        $this->setEnvironment($request);
        $addressValidationReply = $request->getAddressValidationReply($addressValidationRequest);
        if ($addressValidationReply->HighestSeverity === 'SUCCESS') {
            return $addressValidationReply->AddressResults[0]->toArray();
        }
    }

    public function shipment(Shipment $shipment)
    {
        $shipService = resolve(ShipService::class);
        $credentials = $shipService->authenticate();
        $version = $shipService->setVersion();
        $shipper = $shipService->setShipper($shipment);
        $recipient = $shipService->setRecipient($shipment);
        $labelSpecification = $shipService->setSpecifications();
        $packageLineItem = $shipService->setPackage($shipment);
        $shippingChargesPayor = $shipService->setPayor($shipper);
        $shippingChargesPayment = $shipService->setPayment($shippingChargesPayor);
        $requestedShipment = $shipService->requestShipment($shipper, $recipient, $labelSpecification, $packageLineItem, $shippingChargesPayment);
        $processedShipment = $shipService->processShipment($credentials['webAuthenticationDetail'], $credentials['clientDetail'], $version, $requestedShipment);
        $shipRequest = resolve(\FedEx\ShipService\Request::class);
        $this->setEnvironment($shipRequest);
        return $result = $shipRequest->getProcessShipmentReply($processedShipment);
        //Save .pdf label
//        file_put_contents('/path/to/label.pdf', $result->CompletedShipmentDetail->CompletedPackageDetails[0]->Label->Parts[0]->Image);
//        var_dump($result->CompletedShipmentDetail->CompletedPackageDetails[0]->Label->Parts[0]->Image);
    }

    public function setEnvironment($request)
    {
        return $request->getSoapClient()->__setLocation(Request::TESTING_URL);
    }
}
