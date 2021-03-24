@extends('layouts.app', ['title' => 'Products', 'bodyClass' => 'header-primary'])

@section('content')
    <div class="page-header text-white bg-primary">
        <div class="container pt-3 pt-md-4 pb-6 mb-4">
            <h1 class="h5 m-0">All Products</h1>
        </div>
    </div>

    <div class="container">
        @include('profile.partials.product_filter')
        <div class="row match-height filtered_products" class="mx-1">
            @foreach($products as $product)
                @include('profile.partials.product_card')
            @endforeach
        </div>
    </div>
@endsection
