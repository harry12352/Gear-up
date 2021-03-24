@extends('layouts.app', ['title' => 'Order Successful'])
@section('content')
    <div class="container payment-success pb-5">

        <div class="shadow rounded bg-success text-white">
            <div class="py-4">
                <div class="w-100px mx-auto mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><title>ionicons-v5-e</title>
                        <path fill="#fff" d="M256,48C141.31,48,48,141.31,48,256s93.31,208,208,208,208-93.31,208-208S370.69,48,256,48ZM364.25,186.29l-134.4,160a16,16,0,0,1-12,5.71h-.27a16,16,0,0,1-11.89-5.3l-57.6-64a16,16,0,1,1,23.78-21.4l45.29,50.32L339.75,165.71a16,16,0,0,1,24.5,20.58Z"/>
                    </svg>
                </div>
                <h1 class="text-center font-weight-light">{{ __('Thank you Milton!') }}</h1>
                <h3 class="text-center font-weight-normal h4 mt-2">{{ __('Your order has been placed') }}</h3>
                <div class="d-flex mail-note mx-auto mt-4">
                    <div class="w-90px mr-3">@include('global.partials.icon', ['icon' => 'unread-email'])</div>
                    <p>{{ __('An email receipt including the details about your order has been sent to your email address. Please keep it for your records') }}</p>
                </div>
            </div>
            <div class="px-4 py-5 bg-white">
                <div class="bg-light border rounded success-summary mx-auto text-dark p-3">
                    <h2 class="h5">{{ __('Order Summary') }}</h2>
                    <div class="d-sm-flex">
                        <div class="summary-product-img mr-sm-3 mb-3 mb-sm-0"><img class="w-100 rounded" src="http://localhost:8000/storage/images/ba642f_brooke-cagle-emlkhdeydhg-unsplash-540x.jpg" alt=""></div>
                        <div>
                            <h4 class="m-0 font-weight-normal">Modi dolor ut aliqui</h4>
                            <p class="mb-1">$990.00</p>
                            <div class="productInfo--meta small">
                                <div class="font-weight-bold">Size:&nbsp; <span class="font-weight-normal">Large</span></div>
                                <div class="font-weight-bold">Color:&nbsp;
                                    <span class="font-weight-normal">Indian Red, Light Cyan, Old Lace, Orchid, Steel Blue</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="productInfo--price">
                        <div class="d-flex flex-wrap small pt-1">
                            <div class="w-50 font-weight-bold">Price</div>
                            <div class="w-50 text-right">$990.00</div>
                        </div>
                        <div class="d-flex flex-wrap small pt-1">
                            <div class="w-50 font-weight-bold">Shipping</div>
                            <div class="w-50 text-right">$5.00</div>
                        </div>
                        <div class="d-flex flex-wrap pt-3 pb-2">
                            <div class="w-50 font-weight-bold">Total</div>
                            <div class="w-50 text-right">$995.00</div>
                        </div>
                    </div>

                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('feed') }}" class="btn btn-primary">Back to Shopping</a><br>
                    <a href="#print" class="d-inline-block mt-3 print-receipt"> <span class="d-inline-block w-20px">@include('global.partials.icon', ['icon' => 'printer'])</span> Print receipt</a>
                </div>
            </div>
        </div>
    </div>
@endsection
