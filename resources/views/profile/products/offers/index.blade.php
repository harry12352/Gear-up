@extends('layouts.app', ['title' => $product->title . ' Offer'])

@section('content')



    {{--  Checking if user can Offer  --}}
    @php $canUserOffer = true; @endphp
    @if(App\Models\User::hasUserMadeOffer(Auth::user(), $product))
        @php $canUserOffer = false; @endphp
    @endif
    @if(Auth::user()->id === $product->user->id)
        @php $canUserOffer = false; @endphp
    @endif
    <div class="container">
        <div class="row">
            <div class="col-12 mb-3">
                <a class="btn btn-back btn-light btn-sm btn-with-icon" href="{{ route('products.show', ['user' => $user->username, 'product' => $product]) }}">
                    @include('global.partials.icon', ['icon' => 'chevron_left'])
                    Back to {{ $product->title }}
                </a>
            </div>
            <div class="col-12">
                <h1 class="h4">"{{ $product->title }}"Offers</h1>
                <hr>
            </div>
            @if(count($offers) > 0)
                @foreach($offers as $offer)
                    @php
                        $offerUser = $offer->user;
                    @endphp
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card">
                            @isset($offerUser->profileImage)
                                <img class="card-img-top" src="{{ asset('storage/' . $offerUser->profileImage->path) }}" alt="{{ $offerUser->first_name }} {{ $offerUser->last_name }}">
                            @else
                                <img width="200" height="200" class="border img-fluid rounded-circle" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQYV2P48fPXfwAJwwPruND9lQAAAABJRU5ErkJggg==" alt="{{ $offerUser->first_name }} {{ $offerUser->last_name }}">
                            @endisset

                            <div class="card-body">
                                <h5 class="card-title">{{ $offerUser->first_name }} {{ $offerUser->last_name }}</h5>
                                <p class="card-text">
                                    Member since {{ $offerUser->created_at->diffForHumans() }}
                                    <br>
                                    Offer <span class="text-dark">${{ number_format($offer->offered_price, 2) }}</span>
                                </p>
                                <button data-toggle="modal" data-target="#reviewOffer_{{ $offer->id }}_{{ $offerUser->id }}" class="btn btn-outline-primary btn-block">Review Offer</button>
                            </div>
                        </div>


                        <!-- Offer Offer Modal -->
                        <div class="modal fade" id="reviewOffer_{{ $offer->id }}_{{ $offerUser->id }}" tabindex="-1" aria-labelledby="reviewOfferLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="get" action="{{ route('offer.accept', ['user' => $user, 'product' => $product, 'offer' => $offer]) }}">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="reviewOfferLabel">Review {{ $offerUser->first_name }} {{ $offerUser->last_name }} offer</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-4 col-lg-4 col-sm-12">
                                                    @if($product->files)
                                                        <img class="w-100 border h-80px img-cover" src="{{ Storage::url($product->files[0]->path) }}" alt="{{ $product->title }}">
                                                    @else
                                                        <img class="border w-100 h-80px" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQYV2P48fPXfwAJwwPruND9lQAAAABJRU5ErkJggg==" alt="{{ $product->title }}">
                                                    @endif
                                                </div>
                                                <div class="col-md-8 col-lg-8 col-sm-12">
                                                    <h2 class="h5">{{ $product->title }}</h2>
                                                    <h4 class="h6 font-weight-normal">Listing price: ${{ number_format($product->price, 2) }}</h4>
                                                </div>

                                            </div>
                                            <div class="form-group mt-4">
                                                <p>Offer: <span class="h3 pt-1">${{ number_format($offer->offered_price, 2) }}</span></p>
                                                <small id="emailHelp" class="form-text text-muted">You can accept or decline their offer, or you can close the modal if you don't want to make any decision yet. The person who sent you offer will be notified in any case. If you accept their offer, payment will be processed.</small>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" onclick="event.preventDefault();document.getElementById('decline-offer').submit();" class="btn btn-outline-danger">Decline</button>
                                            <button type="submit" class="btn btn-success">Accept</button>
                                        </div>
                                    </form>
                                    <form id="decline-offer" method="get" action="{{ route('offer.decline', ['user' => $user, 'product' => $product, 'offer' => $offer]) }}">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>


                    </div>
                @endforeach
            @else
                <div class="col-12 mt-5">
                    <div class="text-center text-muted font-italic mb-3">No offers has been made on product "{{ $product->title }}"</div>
                </div>
            @endif
        </div>
    </div>

@endsection
