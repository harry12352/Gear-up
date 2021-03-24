<?php

namespace App\Services\Shipping\Fedex\Services;

use FedEx\AddressValidationService\ComplexType;

class AddressValidationService
{
    public function authenticate($addressValidationRequest)
    {
        $addressValidationRequest->WebAuthenticationDetail->UserCredential->Key = config('services.fedex.key');
        $addressValidationRequest->WebAuthenticationDetail->UserCredential->Password = config('services.fedex.pass');
        $addressValidationRequest->ClientDetail->AccountNumber = config('services.fedex.accNo');
        $addressValidationRequest->ClientDetail->MeterNumber = config('services.fedex.meterNo');
    }

    public function setVersion($addressValidationRequest)
    {
        $addressValidationRequest->Version->ServiceId = 'aval';
        $addressValidationRequest->Version->Major = 4;
        $addressValidationRequest->Version->Intermediate = 0;
        $addressValidationRequest->Version->Minor = 0;
    }

    public function addressToValidate($addressValidationRequest, $address)
    {
        $addressValidationRequest->AddressesToValidate = [new ComplexType\AddressToValidate()]; // just validating 1 address in this example.
        $addressValidationRequest->AddressesToValidate[0]->Address->StreetLines = $address['street_lines'];
        $addressValidationRequest->AddressesToValidate[0]->Address->City = $address['city'];
        $addressValidationRequest->AddressesToValidate[0]->Address->StateOrProvinceCode = $address['state_or_province'];
        $addressValidationRequest->AddressesToValidate[0]->Address->PostalCode = $address['postal_code'];
        $addressValidationRequest->AddressesToValidate[0]->Address->CountryCode = $address['country_code'];
    }
}
