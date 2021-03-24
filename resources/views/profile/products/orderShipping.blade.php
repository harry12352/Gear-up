@extends('layouts.app', ['title' => 'Order shipping details | '. $product->title])
@section('content')
    <div class="container single-product-shipping pb-5">
        <div class="shadow rounded bg-white px-4 py-3">


            <div class="row justify-content-between pr-1">
                <div class="col-md-7 order-md-0 order-2 col-lg-6 pt-md-0 pt-4">
                    <h1 class="font-weight-light h2 pb-3 d-md-block d-none">Checkout</h1>

                    <h3 class="h5 mt-4 pb-3">Shipping Address</h3>

                    <form method="POST" action="{{ route('register') }}" class="mb-4">
                        @csrf


                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="first_name">{{ __('First Name') }}</label>
                                <input type="text" name="first_name" id="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required autofocus>
                                @error('first_name')
                                <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="last_name">{{ __('Last Name') }}</label>
                                <input type="text" name="last_name" id="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                                @error('last_name')
                                <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group pt-md-0 pt-3">
                            <label for="username">{{ __('Address') }}</label>
                            <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" required>
                            @error('username')
                            <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="country">{{ __('Country') }}</label>
                                <input type="text" name="country" id="country" class="form-control @error('country') is-invalid @enderror" value="{{ old('country') }}" required>
                                @error('country')
                                <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="state">{{ __('State') }}</label>
                                <input type="text" name="state" id="state" class="form-control @error('state') is-invalid @enderror" value="{{ old('state') }}" required>
                                @error('state')
                                <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row pt-md-0 pt-3">
                            <div class="form-group col-md-6">
                                <label for="city">{{ __('City') }}</label>
                                <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}" required>
                                @error('city')
                                <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="zip_code">{{ __('Zip code') }}</label>
                                <input type="text" name="zip_code" id="zip_code" class="form-control @error('zip_code') is-invalid @enderror" value="{{ old('zip_code') }}" required>
                                @error('zip_code')
                                <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end pt-md-0 pt-3">
                            <button type="submit" class="btn btn-primary">Review Order</button>
                        </div>

                    </form>
                </div>
                <div class="col-md-4 col-lg-3 position-relative productInfo orderShipping-product pr-0">
                    <div class="orderShipping-product--wrap position-relative">
                        <h1 class="font-weight-light h2 pb-3 d-block d-md-none">Checkout</h1>
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
                                <div class="w-50 text-right">Calculated at next step</div>
                            </div>
                            <div class="d-flex flex-wrap pt-3 pb-2">
                                <div class="w-50 font-weight-bold">Total</div>
                                <div class="w-50 text-right">${{ number_format($product->price, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
