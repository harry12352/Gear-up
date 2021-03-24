@extends('layouts.app', ['title' => $user->first_name . ' ' .$user->last_name])

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="user-profile-card">
                    <div class="row">
                        @include('profile.partials.profile_card', ["tab" => "products"])
                    </div>
                </div>
                <div class="profile-data profile-products mt-4">
                    @if($user->products)
                        <div class="row">
                            @foreach($user->products as $product)
                                @php
                                    $product = $product->productData
                                @endphp

                            @endforeach

                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
