@extends('layouts.app', ['title' => $category->name . ' Products'])

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 px-lg-2 mb-3">
                <h1 class="h4 m-0 category-title align-middle">{{ $category->name }}

                    @if(Auth::user())
                        @if($category->isCategoryFollowed($category))
                            &nbsp;&nbsp;<a href="{{ route('category.unfollow', ['category' => $category]) }}" class="unfollow-category btn py-0 mb-1 btn-sm btn-outline-primary">Unfollow Category</a>
                        @else
                            &nbsp;&nbsp;<a href="{{ route('category.follow', ['category' => $category]) }}" class="follow-category btn py-0 mb-1 btn-sm btn-primary">Follow Category</a>
                        @endif
                    @endif
                </h1>
                <span class="float-right mt-n4">Showing @if($products->lastPage() > 1) {{ $products->perPage() }} @else {{ $products->total() }} @endif of {{ $products->total() }} products</span>

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
