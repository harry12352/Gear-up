@extends('layouts.app', ['title' => 'Brands ' . $brand->name])

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 px-lg-2 mb-3">
                <h1 class="h4 m-0 category-title">{{ $brand->name }}</h1><span class="float-right mt-n4">Showing @if($products->lastPage() > 1) {{ $products->perPage() }} @else {{ $products->total() }} @endif of {{ $products->total() }} products</span>
                <br>
                @if($brand->isBrandFollowed($brand))
                    <a href="{{ route('brand.unfollow', ['brand' => $brand]) }}" class="btn btn-outline-primary">Unfollow Brand</a>
                @else
                    <a href="{{ route('brand.follow', ['brand' => $brand]) }}" class="btn btn-primary">Follow Brand</a>
                @endif
            </div>
            @foreach($products as $product)
                @include('profile.partials.product_card')
            @endforeach
            <div class="col-12 px-lg-2">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection
