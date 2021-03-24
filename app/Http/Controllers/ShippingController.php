<?php

namespace App\Http\Controllers;

use App\Models\ShippingInformation;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ShippingController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function shipping() {
        $user_id = Auth::user()->id;
        $shippingInformation = ShippingInformation::where('user_id', $user_id)->get()->first();
        return view('settings.shipping', ['shipping' => $shippingInformation]);
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {

        return Validator::make($data, [
            'address' => ['required', 'string', 'max:255'],
            'address_2' => ['nullable', 'string', 'max:255'],
            'zip_code' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param Request $request
     * @return \App\Models\User
     */
    protected function updateShipping(Request $request) {
        $inputs_data = $request->all();
        $validator = $this->validator($inputs_data);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user_id = Auth::user()->id;
        $current_shippingInformation = ShippingInformation::where('user_id', $user_id)->get()->first();
        if ($current_shippingInformation) {
            ShippingInformation::where('user_id', $user_id)->update(
                [
                    'address' => $request->input('address'),
                    'address_2' => $request->input('address_2'),
                    'zip_code' => $request->input('zip_code'),
                    'city' => $request->input('city'),
                    'state' => $request->input('state'),
                    'country' => $request->input('country')
                ]
            );
        } else {
            ShippingInformation::create(
                [
                    'user_id' => $user_id,
                    'address' => $request->input('address'),
                    'address_2' => $request->input('address_2'),
                    'zip_code' => $request->input('zip_code'),
                    'city' => $request->input('city'),
                    'state' => $request->input('state'),
                    'country' => $request->input('country')
                ]
            );
        }

        $request->session()->flash('success', 'Shipping information saved successfully');
        return redirect()->back()->withInput();
    }
}
