@extends('layouts.app', ["title" => "Shipping Address"])

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class='col-sm-10'>
                <div class="settings-title mb-4">
                    <h1 class="h4">Settings</h1>
                </div>

                @include('settings.settings_nav', ["tab" => "shipping"])
                <div class="tab-content settings-tab-content py-3 px-3 px-sm-0">
                    <div class="tab-pane show active p-1">

                        <div class="row">
                            <div class='col-sm-7'>

                                <form action="{{route('settings.shipping')}}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input type="text" class="form-control @error('address') is-invalid @enderror" required id="address" name="address" value="{{old('address', $shipping->address ?? '')}}">
                                        @error('address') <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span> @enderror

                                    </div>
                                    <div class="form-group">
                                        <label for="zip_code">Zip code</label>
                                        <input type="text" class="form-control @error('zip_code') is-invalid @enderror" required id="zip_code" name="zip_code" value="{{old('zip_code', $shipping->zip_code ?? '')}}">
                                        @error('zip_code') <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <input type="text" class="form-control @error('city') is-invalid @enderror" required id="city" name="city" value="{{old('city', $shipping->city ?? '')}}">
                                        @error('city') <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="state">State</label>
                                        <input type="text" class="form-control @error('state') is-invalid @enderror" required id="state" name="state" value="{{old('state', $shipping->state ?? '')}}">
                                        @error('state') <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="country">Country</label>
                                        <select class="form-control @error('country') is-invalid @enderror" required id="country" name="country" value="{{old('country', $shipping->country ?? '')}}">
                                            <option value="UK">United Kingdom</option>
                                            <option value="US">United States</option>
                                        </select>
                                        @error('country') <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span> @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


@endsection
