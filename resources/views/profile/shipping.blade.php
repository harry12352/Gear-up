@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class='col-sm-10'>
                <div class="settings-title mb-4">
                    <h1 class="h4">Settings</h1>
                </div>

                <nav>
                    <div class="nav nav-tabs settings-tab" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link " href="{{ route('profile.account') }}" aria-selected="false">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                                <path d="M 16 5 C 12.144531 5 9 8.144531 9 12 C 9 14.410156 10.230469 16.550781 12.09375 17.8125 C 8.527344 19.34375 6 22.882813 6 27 L 8 27 C 8 22.570313 11.570313 19 16 19 C 20.429688 19 24 22.570313 24 27 L 26 27 C 26 22.882813 23.472656 19.34375 19.90625 17.8125 C 21.769531 16.550781 23 14.410156 23 12 C 23 8.144531 19.855469 5 16 5 Z M 16 7 C 18.773438 7 21 9.226563 21 12 C 21 14.773438 18.773438 17 16 17 C 13.226563 17 11 14.773438 11 12 C 11 9.226563 13.226563 7 16 7 Z"/>
                            </svg>
                            {{ __('Account') }}
                        </a>
                        <a class="nav-item nav-link" href="{{ route('profile.password') }}" aria-selected="false">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                                <path d="M 16 3 C 12.15625 3 9 6.15625 9 10 L 9 13 L 6 13 L 6 29 L 26 29 L 26 13 L 23 13 L 23 10 C 23 6.15625 19.84375 3 16 3 Z M 16 5 C 18.753906 5 21 7.246094 21 10 L 21 13 L 11 13 L 11 10 C 11 7.246094 13.246094 5 16 5 Z M 8 15 L 24 15 L 24 27 L 8 27 Z"/>
                            </svg>
                            {{ __('Security') }}
                        </a>
                        <a class="nav-item nav-link active" href="{{ route('profile.shipping') }}" aria-selected="true">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                                <path
                                    d="M 3 6 L 3 26 L 29 26 L 29 6 Z M 5 8 L 27 8 L 27 24 L 23.59375 24 C 23.515625 23.863281 23.550781 23.675781 23.4375 23.5625 C 23.058594 23.183594 22.523438 23 22 23 C 21.476563 23 20.941406 23.183594 20.5625 23.5625 C 20.449219 23.675781 20.484375 23.863281 20.40625 24 L 11.59375 24 C 11.515625 23.863281 11.550781 23.675781 11.4375 23.5625 C 11.058594 23.183594 10.523438 23 10 23 C 9.476563 23 8.941406 23.183594 8.5625 23.5625 C 8.449219 23.675781 8.484375 23.863281 8.40625 24 L 5 24 Z M 12 10 C 9.800781 10 8 11.800781 8 14 C 8 15.113281 8.476563 16.117188 9.21875 16.84375 C 7.886719 17.746094 7 19.285156 7 21 L 9 21 C 9 19.34375 10.34375 18 12 18 C 13.65625 18 15 19.34375 15 21 L 17 21 C 17 19.285156 16.113281 17.746094 14.78125 16.84375 C 15.523438 16.117188 16 15.113281 16 14 C 16 11.800781 14.199219 10 12 10 Z M 12 12 C 13.117188 12 14 12.882813 14 14 C 14 15.117188 13.117188 16 12 16 C 10.882813 16 10 15.117188 10 14 C 10 12.882813 10.882813 12 12 12 Z M 19 13 L 19 15 L 25 15 L 25 13 Z M 19 17 L 19 19 L 25 19 L 25 17 Z"/>
                            </svg>
                            {{ __('Shipping Address') }}
                        </a>
                        <a class="nav-item nav-link" href="" aria-selected="false">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                                <path d="M 16 3.21875 L 15.125 4.71875 L 3.125 25.5 L 2.28125 27 L 29.71875 27 L 28.875 25.5 L 16.875 4.71875 Z M 16 7.21875 L 26.25 25 L 5.75 25 Z M 15 14 L 15 20 L 17 20 L 17 14 Z M 15 21 L 15 23 L 17 23 L 17 21 Z"/>
                            </svg>
                            {{ __('Delete Account') }}
                        </a>
                    </div>
                </nav>
                <div class="tab-content py-3 px-3 px-sm-0">
                    <div class="tab-pane show active p-1">

                        <div class="row">
                            <div class='col-sm-7'>

                                <form action="{{route('profile.shipping')}}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input type="text" class="form-control @error('address') is-invalid @enderror" required id="address" name="address" value="{{old('address', $shipping->address ?? '')}}">
                                        @error('address') <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span> @enderror

                                    </div>
                                    <div class="form-group">
                                        <label for="address_2">Address 2</label>
                                        <input type="text" class="form-control @error('address_2') is-invalid @enderror" id="address_2" name="address_2" value="{{old('address_2', $shipping->address_2 ?? '') }}">
                                        @error('address_2') <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span> @enderror
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
