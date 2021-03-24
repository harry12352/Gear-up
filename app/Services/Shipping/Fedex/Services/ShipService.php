<?php

namespace App\Services\Shipping\Fedex\Services;

use App\Services\Shipping\Usps\Shipment;
use FedEx\ShipService\ComplexType;
use FedEx\ShipService\SimpleType;

class ShipService
{

    public function authenticate()
    {
        $userCredential = new ComplexType\WebAuthenticationCredential();
        $userCredential
            ->setKey(config('services.fedex.key'))
            ->setPassword(config('services.fedex.pass'));

        $webAuthenticationDetail = new ComplexType\WebAuthenticationDetail();
        $webAuthenticationDetail->setUserCredential($userCredential);

        $clientDetail = new ComplexType\ClientDetail();
        $clientDetail
            ->setAccountNumber(config('services.fedex.accNo'))
            ->setMeterNumber(config('services.fedex.meterNo'));

        return ['webAuthenticationDetail' => $webAuthenticationDetail, 'clientDetail' => $clientDetail];
    }

    public function setVersion()
    {
        $version = new ComplexType\VersionId();
        $version
            ->setMajor(23)
            ->setIntermediate(0)
            ->setMinor(0)
            ->setServiceId('ship');
        return $version;
    }

    public function setShipper(Shipment $shipment)
    {
        $shipperAddress = new ComplexType\Address();
        $shipperAddress
            ->setStreetLines([$shipment->origin['street_lines']])
            ->setCity($shipment->origin['city'])
            ->setStateOrProvinceCode($shipment->origin['state_or_province'])
            ->setPostalCode($shipment->origin['postal_code'])
            ->setCountryCode($shipment->origin['country_code']);

        $shipperContact = new ComplexType\Contact();
        $shipperContact
            ->setCompanyName('Company Name')
            ->setEMailAddress('test@example.com')
            ->setPersonName('Person Name')
            ->setPhoneNumber(('123-123-1234'));

        $shipper = new ComplexType\Party();
        $shipper
            ->setAccountNumber(config('services.fedex.accNo'))
            ->setAddress($shipperAddress)
            ->setContact($shipperContact);
        return $shipper;
    }

    public function setRecipient(Shipment $shipment)
    {
        $recipientAddress = new ComplexType\Address();
        $recipientAddress
            ->setStreetLines([$shipment->destination['street_lines']])
            ->setCity($shipment->destination['city'])
            ->setStateOrProvinceCode($shipment->destination['state_or_province'])
            ->setPostalCode($shipment->destination['postal_code'])
            ->setCountryCode($shipment->destination['country_code']);

        $recipientContact = new ComplexType\Contact();
        $recipientContact
            ->setPersonName('Contact Name')
            ->setPhoneNumber('1234567890');

        $recipient = new ComplexType\Party();
        $recipient
            ->setAddress($recipientAddress)
            ->setContact($recipientContact);
        return $recipient;
    }

    public function setSpecifications()
    {
        $labelSpecification = new ComplexType\LabelSpecification();
        $labelSpecification
            ->setLabelStockType(new SimpleType\LabelStockType(SimpleType\LabelStockType::_PAPER_7X4POINT75))
            ->setImageType(new SimpleType\ShippingDocumentImageType(SimpleType\ShippingDocumentImageType::_PDF))
            ->setLabelFormatType(new SimpleType\LabelFormatType(SimpleType\LabelFormatType::_COMMON2D));
        return $labelSpecification;
    }

    public function setPackage(Shipment $shipment)
    {
        $packageLineItem = new ComplexType\RequestedPackageLineItem();
        $packageLineItem
            ->setSequenceNumber(1)
            ->setItemDescription('Product description')
            ->setDimensions(new ComplexType\Dimensions(array(
                'Width' => $shipment->packages[0]['dimension_width'],
                'Height' => $shipment->packages[0]['dimension_height'],
                'Length' => $shipment->packages[0]['dimension_length'],
                'Units' => SimpleType\LinearUnits::_IN
            )))
            ->setWeight(new ComplexType\Weight(array(
                'Value' => $shipment->packages[0]['weight_count'],
                'Units' => SimpleType\WeightUnits::_LB
            )));

        return $packageLineItem;
    }

    public function setPayor($shipper)
    {
        $shippingChargesPayor = new ComplexType\Payor();
        $shippingChargesPayor->setResponsibleParty($shipper);
        return $shippingChargesPayor;

    }

    public function setPayment($shippingChargesPayor)
    {
        $shippingChargesPayment = new ComplexType\Payment();
        $shippingChargesPayment
            ->setPaymentType(SimpleType\PaymentType::_SENDER)
            ->setPayor($shippingChargesPayor);
        return $shippingChargesPayment;
    }

    public function requestShipment($shipper, $recipient, $labelSpecification, $packageLineItem, $shippingChargesPayment)
    {
        $requestedShipment = new ComplexType\RequestedShipment();
        $requestedShipment->setShipTimestamp(date('c'));
        $requestedShipment->setDropoffType(new SimpleType\DropoffType(SimpleType\DropoffType::_REGULAR_PICKUP));
        $requestedShipment->setServiceType(new SimpleType\ServiceType(SimpleType\ServiceType::_FEDEX_GROUND));
        $requestedShipment->setPackagingType(new SimpleType\PackagingType(SimpleType\PackagingType::_YOUR_PACKAGING));
        $requestedShipment->setShipper($shipper);
        $requestedShipment->setRecipient($recipient);
        $requestedShipment->setLabelSpecification($labelSpecification);
        $requestedShipment->setRateRequestTypes(array(new SimpleType\RateRequestType(SimpleType\RateRequestType::_PREFERRED)));
        $requestedShipment->setPackageCount(1);
        $requestedShipment->setRequestedPackageLineItems([$packageLineItem]);
        $requestedShipment->setShippingChargesPayment($shippingChargesPayment);
        return $requestedShipment;
    }

    public function processShipment($webAuthenticationDetail, $clientDetail, $version, $requestedShipment)
    {
        $processShipmentRequest = new ComplexType\ProcessShipmentRequest();
        $processShipmentRequest->setWebAuthenticationDetail($webAuthenticationDetail);
        $processShipmentRequest->setClientDetail($clientDetail);
        $processShipmentRequest->setVersion($version);
        $processShipmentRequest->setRequestedShipment($requestedShipment);
        return $processShipmentRequest;
    }
}
