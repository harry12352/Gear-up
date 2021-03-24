<?php

namespace App\Http\Controllers\Setting;

use Illuminate\Support\Facades\Auth;
use App\Models\ShippingInformation;
use App\Services\Shipping\Fedex\Fedex;

class ShippingSettingsController extends SettingsController
{
    protected $view = "settings.shipping";

    protected $validationRules = [
        'address' => ['required', 'string', 'max:255'],
        'zip_code' => ['required', 'string', 'max:255'],
        'city' => ['required', 'string', 'max:255'],
        'state' => ['required', 'string', 'max:255'],
        'country' => ['required', 'string', 'max:255'],
    ];

    public function showSettingsForm()
    {
        $data = [
            "title" => "Shipping Address",
            "tab" => "shipping",
            "user" => Auth::user()
        ];
        return view($this->view, $data);
    }

    public function saveSettings($userInput)
    {
        // Validating shipping info.
        if (!$this->validateShipping($userInput)) {
            return back()->with('error', ['Unable to validate address as shipping address.']);
        }
        // Updating shipping info.
        $this->updateShipping($userInput);

        return back()->with('success', 'Shipping information saved successfully');
    }

    private function validateShipping(array $userInput)
    {
        /** @var Fedex */
        $fedex = resolve(Fedex::class);
        $preferredCurrency = "GB";
        if ($userInput['country'] == "US") {
            $preferredCurrency = "USD";
        }
        $address = [
            'prefered_currency' => $preferredCurrency,
            'street_lines' => $userInput['address'],
            'city' => $userInput['city'],
            'state_or_province' => $userInput['state'],
            'postal_code' => $userInput['zip_code'],
            'country_code' => $userInput['country'],
        ];
        return "standardized" == strtolower($fedex->validateShipping($address)['State']);
    }

    protected function updateShipping($userInput)
    {
        $user_id = Auth::user()->id;
        $current_shippingInfo = ShippingInformation::where('user_id', $user_id)->get()->first();
        $newShippingInfo = [
            'address' => $userInput['address'],
            'zip_code' => $userInput['zip_code'],
            'city' => $userInput['city'],
            'state' => $userInput['state'],
            'country' => $userInput['country']
        ];

        if ($current_shippingInfo) {
            return ShippingInformation::where('user_id', $user_id)->update($newShippingInfo);
        } else {
            $newShippingInfo["user_id"] = (int) $user_id;
            return ShippingInformation::create($newShippingInfo);
        }
    }
}
