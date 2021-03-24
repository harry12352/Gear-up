@extends('layouts.app')

@section('content')
    <div class="container p-0">
        <div class="row match-height" class="mx-1">
            <div class="col-12">
                @if( request()->get('q') )
                    <p class="lead">Search results for <b>"{{ request()->get('q') }}"</b></p>
                @endif
            </div>
            @foreach($products as $product)
                @include('profile.partials.product_card')
            @endforeach
        </div>
    </div>
@endsection
