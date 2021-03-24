@extends('layouts.app', ['title' => 'My Offers'])
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1 class="h5 m-0">Your Offers</h1>
            </div>
            <div class="card-body">
                <table class="table table-hover border-top-0">
                    <thead>
                    <tr>
                        <th class="border-top-0" scope="col">#</th>
                        <th class="border-top-0" scope="col">Product</th>
                        <th class="border-top-0" scope="col">Price</th>
                        <th class="border-top-0" scope="col">Offer</th>
                        <th class="border-top-0" scope="col">Status</th>
                        <th class="border-top-0" scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($userOffers) > 0 )
                    @foreach($userOffers as $index => $userOffer)
                        <tr>
                            <th scope="row"><span class="align-middle">{{ $index+1 }}</span></th>
                            <td><span class="align-middle"><a href="{{ route('products.show', ['user' => $userOffer->product->user->username, 'product' => $userOffer->product->slug]) }}">{{ $userOffer->product->title}}</a></span></td>
                            <td><span class="align-middle">${{ number_format($userOffer->product->price, 2) }}</span></td>
                            <td><span class="align-middle">${{ number_format($userOffer->offered_price, 2) }}</span></td>
                            <td>@if($userOffer->status === 'pending') <span class="badge align-middle badge-info text-uppercase">{{ $userOffer->status }}</span> @elseif($userOffer->status === 'declined') <span class="badge badge-danger text-uppercase">{{ $userOffer->status }}</span> @endif</td>
                            <td>
                                @if($userOffer->status !== 'accepted' && $userOffer->status !== 'declined')
                                    <a href="{{route('offer.delete',['user' => $user, 'product' => $userOffer['product_id'],'offer'=>$userOffer['id']])}}"
                                       class="btn align-middle btn-sm btn-outline-danger">Delete Offer</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
