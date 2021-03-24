@extends('layouts.app', ['title' => 'Confirm your order | '. $product->title])
@section('content')
    <div class="container single-product-confirm-order">
        <div class="shadow rounded bg-white px-4 py-3">


            <div class="row justify-content-between pr-1">
                <div class="col-md-8 order-md-0 order-2 pt-md-0 pt-4">
                    <h1 class="font-weight-light h2 pb-3 d-md-block d-none">{{ __('Checkout') }} </h1>

                    <h3 class="h5 mt-4 pb-3 d-md-block d-none">{{ __('Confirm your order details') }}</h3>

                    <div class="order-meta row justify-content-between d-md-flex d-none">
                        <div class="col-md-3">
                            <span class="order-meta-title d-block small font-weight-bold">Order ID</span>
                            <span class="order-meta-details d-block text-primary">#47783</span>
                        </div>
                        <div class="col-md-3">
                            <span class="order-meta-title d-block small font-weight-bold">Date</span>
                            <span class="order-meta-details d-block text-primary">26 October, 2020</span>
                        </div>
                        <div class="col-md-3">
                            <span class="order-meta-title d-block small font-weight-bold">Total</span>
                            <span class="order-meta-details d-block text-primary">$995.00</span>
                        </div>
                        <div class="col-md-3">
                            <span class="order-meta-title d-block small font-weight-bold">Payment Method</span>
                            <span class="order-meta-details d-block text-primary">PayPal</span>
                        </div>
                    </div>

                    <div class="order-customer mt-4 rounded bg-light border p-3">
                        <h2 class="h5 font-weight-normal">{{ __('Customer Details') }}</h2>
                        <div class="order-customer--details font-weight-light font-italic">
                            <p class="mb-1">John Park</p>
                            <p class="mb-1">johnpark32@gmail.com</p>
                            <p class="mb-1">+34 254 5645 345</p>
                        </div>
                    </div>
                    <div class="order-shipping mt-4 rounded bg-light border p-3 mb-4">
                        <h2 class="h5 font-weight-normal">{{ __('Shipping Address') }}</h2>
                        <div class="order-customer--details font-weight-light font-italic">
                            <p class="mb-1">1407 Broaddus Avenue</p>
                            <p class="mb-1">Kentucky</p>
                            <p class="mb-1">Bowling Green</p>
                            <p class="mb-1">42101</p>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end justify-content-md-start">
                        <a href="{{ route('product.buy', ['user' => $user, 'product' => $product ]) }}" class="btn btn-primary">{{ __('Continue to Payment') }}</a>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3 position-relative productInfo orderShipping-product pr-0">
                    <div class="orderShipping-product--wrap position-relative">
                        <h1 class="font-weight-light h2 pb-3 d-block d-md-none">{{ __('Checkout') }}</h1>
                        <h3 class="h5 mt-4 pb-1 d-block d-md-none">{{ __('Confirm your order details') }}</h3>

                        <div class="order-meta row justify-content-between d-flex d-md-none mb-2">
                            <div class="col-md-3 col-sm-6 mb-3">
                                <span class="order-meta-title d-block small font-weight-bold">Order ID</span>
                                <span class="order-meta-details d-block text-primary">#47783</span>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <span class="order-meta-title d-block small font-weight-bold">Date</span>
                                <span class="order-meta-details d-block text-primary">26 October, 2020</span>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <span class="order-meta-title d-block small font-weight-bold">Total</span>
                                <span class="order-meta-details d-block text-primary">$995.00</span>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <span class="order-meta-title d-block small font-weight-bold">Payment Method</span>
                                <span class="order-meta-details d-block text-primary">PayPal</span>
                            </div>
                        </div>


                        <div class="orderShipping-product--img">
                            @if(isset($product->files) && count($product->files) > 0)
                                <img class="w-100 rounded" src="{{ Storage::url($product->files[0]->path) }}" alt="{{ $product->title }}">
                            @endif
                        </div>
                        <h2 class="h4 mt-2">{{ $product->title }}</h2>
                        <p class="lead mb-2">${{ number_format($product->price, 2) }}</p>

                        <div class="productInfo--meta small">
                            @if($product->size)
                                <div class="font-weight-bold">Size:&nbsp; <span class="font-weight-normal">{{ $product->size->name }}</span></div>
                            @endif
                            <div class="font-weight-bold">Color:&nbsp;
                                @if(count($product->colors) > 0)
                                    <span class="font-weight-normal">
                                        @foreach($product->colors as $index => $color)
                                            {{ $color->name }}@if($index != count($product->colors)-1),@endif
                                        @endforeach
                                    </span>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="productInfo--price">
                            <div class="d-flex flex-wrap small pt-1">
                                <div class="w-50 font-weight-bold">Price</div>
                                <div class="w-50 text-right">${{ number_format($product->price, 2) }}</div>
                            </div>
                            <div class="d-flex flex-wrap small pt-1">
                                <div class="w-50 font-weight-bold">Shipping</div>
                                <div class="w-50 text-right">$5.00</div>
                            </div>
                            <div class="d-flex flex-wrap pt-3 pb-2">
                                <div class="w-50 font-weight-bold">Total</div>
                                <div class="w-50 text-right">${{ number_format($product->price+5, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
@endsection
