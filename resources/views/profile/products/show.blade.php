@extends('layouts.app', ['title' => $product->title])
@section('content')
    <div class="container single-product" data-recently="{{ route('add.products', ['id' => $product->id]) }}">
        @if($product->status === 'drafted' && $product->user->id === Auth::user()->id)
            <div class="alert alert-info">This product is not live for public. Please edit this product and fill all details to publish</div>
        @endif

        {{--  Checking if user can Offer  --}}
        @auth
            @php $canUserOffer = true; @endphp
            @if(App\Models\User::hasUserMadeOffer(Auth::user(), $product))
                @php $canUserOffer = false; @endphp
            @endif
            @if(Auth::user()->id === $product->user->id)
                @php $canUserOffer = false; @endphp
            @endif
        @endauth


        <div class="container single-product" data-recently="{{ route('add.products', ['id' => $product->id]) }}">
            @if(Auth::id() === $user->id)
                <div class="row">
                    <div class="col-6 mb-3">
                        <a class="btn btn-back btn-light btn-sm btn-with-icon" href="{{ route('products.index', ['user' => $user->username]) }}">
                            @include('global.partials.icon', ['icon' => 'chevron_left'])
                            Back to Products
                        </a>
                    </div>
                    <div class="col-6 mb-3">
                        <a class="btn btn-back btn-light btn-sm btn-with-icon float-right" href="{{ route('offer.index', ['user' => $user->username, 'product' => $product]) }}">
                            View Offers
                            @include('global.partials.icon', ['icon' => 'chevron_right'])
                        </a>
                    </div>
                </div>
            @endif
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 productImages">
                            <div class="productImages--inner">
                                @foreach($product->files as $productImage)
                                    <div class="productImages--single" data-thumb="{{ Storage::url($productImage->path) }}">
                                        <img src="{{ Storage::url($productImage->path) }}" alt="{{ $product->title }}">
                                    </div>
                                @endforeach
                            </div>

                        </div>
                        <div class="col-md-6 productInfo mt-4 mt-md-0">
                            <h1 class="h4 product-title">{{ $product->title}}</h1>
                            <p class="badge badge-light font-weight-normal text-uppercase"><a href="{{ route('brand.show', $product->brand) }}">{{ $product->brand->name }}</a></p>

                            <div class="productUser pt-1 pb-2 mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    @isset($user->profileImage)
                                        <img class="img-fluid border rounded-circle" src="{{ asset('storage/' . $user->profileImage->path) }}" alt="{{ $user->first_name }} {{ $user->last_name }}">
                                    @else
                                        <img width="200" height="200" class="img-fluid border rounded-circle" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQYV2P48fPXfwAJwwPruND9lQAAAABJRU5ErkJggg==" alt="{{ $user->first_name }} {{ $user->last_name }}">
                                    @endisset
                                    <div class="profile-card--meta w-100 ml-2 mt-1">
                                        <h4 class="profile-card--title m-0 text-truncate"><a class="text-decoration-none" href="{{ route('profile.index', ['user' => $user->username]) }}">{{ $user->first_name }} {{ $user->last_name }}</a></h4>
                                        <div id="productusername" class="sr-only">{{ $user->username }}</div>
                                        <div class="profile-card--username mb-2 text-muted text-truncate">{{ $product->updated_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="productInfo--price"><h2 class="h5">${{ number_format($product->price, 2) }}</h2></div>

                            <div class="productInfo--description pt-2 pb-2">
                                <p class="m-0">{!! nl2br(e($product->description)) !!}</p>
                                <a href="javascript:void(0)" class="d-none">Read More</a>
                            </div>
                            <div class="productInfo--meta pt-4 pb-2">
                                @if($product->size)
                                    <div class="h6">Size:&nbsp; <h5 class="d-inline-block"><span class="badge border badge-light font-weight-normal">{{ $product->size->name }}</span></h5></div>
                                @endif
                                <div class="h6">Color:&nbsp;
                                    @if(count($product->colors) > 0)
                                        @foreach($product->colors as $color)
                                            <h5 class="d-inline-block"><span class="badge border badge-light font-weight-normal product-color-badge product-color-{{ $color->value }}">{{ $color->name }}</span></h5>
                                            <style>.product-color-badge.product-color-{{ $color->value }}:before {
                                                    background-color: {{ $color->value }}
                                                }</style>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="productInfo--form pt-2 pb-2">
                                <div class="row">

                                    @auth
                                        @if(App\Models\User::hasUserMadeOffer(Auth::user(), $product))
                                            <div class="col-md-12 mt-3">
                                                <div class="alert alert-info">You have placed your offer on this product. &nbsp;<a href="{{ route('offers.myoffers', ['user' => Auth::user()]) }}" class="text-info text-decoration-none border-bottom border-info">View Offer</a></div>
                                            </div>
                                        @endif
                                        @if(Auth::id() === $user->id && $product->offers->isNotEmpty() > 0)
                                            <div class="col-md-12 mt-3">
                                                <div class="alert alert-info"><span class="badge badge-info">{{ count($product->offers) }}</span> @if(count($product->offers) > 1) offers @else offer @endif received for this product. &nbsp;<a href="{{ route('offer.index', ['user' => $user, 'product' => $product]) }}" class="text-info text-decoration-none border-bottom border-info">Review offers</a> before they are gone!</div>
                                            </div>
                                        @endif
                                        @if($canUserOffer)
                                            <div class="col-md-6">
                                                <button class="btn btn-block btn-outline-primary make-offer" data-toggle="modal" data-target="#offerModal">Make an Offer</button>
                                            </div>
                                        @endif
                                        @if(Auth::id() === $user->id)
                                            <div class="col-md-6">
                                                <a href="{{ route('products.edit', ['user' => $user, 'product' => $product->slug]) }}" class="btn edit-product btn-block btn-secondary">
                                                    @include('global.partials.icon', ['icon' => 'edit'])
                                                    Edit Product
                                                </a>
                                            </div>
                                        @endif

                                    @endauth
                                    <div class="@auth @if(!$canUserOffer && Auth::id() != $user->id) col-md-12 @elseif(Auth::id() == $user->id)) col-md-6 @else col-md-6 @endif @else col-md-12  @endauth mt-3 mt-md-0">
                                        <a href="{{ route('product.orderShipping', ['user' => $user, 'product' => $product ]) }}" class="btn btn-block btn-primary">Buy Now</a>
                                    </div>

                                </div>
                            </div>
                            <div class="productInfo--share pt-2 pb-2 d-none">
                                <a class="text-decoration-none" href="javascript:void(0)">
                                    @include('global.partials.icon', ['icon' => 'wishlist'])
                                    <span class="d-inline-block align-middle">Add to Wishlist</span>
                                </a>
                                <a class="ml-3 text-decoration-none" href="javascript:void(0)">
                                    @include('global.partials.icon', ['icon' => 'share'])
                                    <span class="d-inline-block align-middle">Share</span>
                                </a>
                            </div>

                            <div class="productInfo--form pt-3">
                                <p class="m-0 pb-1 small text-uppercase">Category:</p>
                                @foreach($product->categories as $category)
                                    <a href="{{ route('category.show', [$category['slug']]) }}" class="btn btn-sm btn-light">{{ $category->name }}</a>
                                @endforeach

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>


        @auth
        <!-- offer Offer Modal -->
            <div class="modal fade" id="offerModal" tabindex="-1" aria-labelledby="offerModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="post" action="{{ route('offer.create', ['user' => Auth::user(), 'product' => $product]) }}">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="offerModalLabel">Make an offer</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="productInfo">
                                <div class="productInfo--meta pt-4 p-2">
                                    <div class="h6">Size:&nbsp; <h5 class="d-inline-block"><span class="badge border badge-light font-weight-normal">{{ $product->size->name }}</span></h5></div>
                                    <div class="h6 m-0">Color:&nbsp; <h5 class="d-inline-block">
                                            @if(count($product->colors) > 0)
                                                @foreach($product->colors as $color)
                                                    <span class="badge border badge-light font-weight-normal product-color-badge product-color-{{ $color->value }}">{{ $color->name }}</span>
                                                @endforeach
                                            @endif
                                        </h5></div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-4 col-lg-4 col-sm-12">
                                        @if($product->files->isNotEmpty())
                                            <img class="w-100 border h-80px img-contain" src="{{ Storage::url($product->files[0]->path) }}" alt="{{ $product->title }}">
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
                                    <label for="offered_price">Your offer</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">$</div>
                                        </div>
                                        <input type="number" class="form-control" id="offered_price" name="offered_price" placeholder="0.00">
                                    </div>
                                    <small id="emailHelp" class="form-text text-muted">Seller can accept or decline your offer, you will be notified in either case. If the seller accepts, payment will be processed.</small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Submit offer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        @endauth

        <div class="container mt-4">
            <div class="card bg-secondary border-0 shadow-sm">
                <div class="card-header border-0 pb-0"><h3 class="h6 m-0 text-white">Reviews</h3></div>
                <div class="card-body">
                    <div class="reviews-wrap text-white">
                        <div class="reviews-list">

                            <ul class="list-unstyled  @if(!Auth::user() || App\Models\Review::reviewExists(Auth::user(), $product) ||  Auth::user()->id === $product->user->id) m-0 @endif">
                                @if($product->reviews && count($product->reviews) > 0)
                                    @foreach($product->reviews as $review)
                                        <li class="single-review d-flex flex-wrap" id="review-{{ $review->id }}">
                                            <div class="review-user w-5">
                                                @isset($review->user->profileImage)
                                                    <img class="rounded-circle border border-dark img-fluid img-cover w-45px h-45px" src="{{ asset('storage/' . $review->user->profileImage->path)}}"
                                                         alt="{{ $review->user->first_name }} {{ $review->user->last_name }}">
                                                @else
                                                    <img class="rounded-circle border border-dark img-fluid img-cover w-45px h-45px"
                                                         src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQYV2P48fPXfwAJwwPruND9lQAAAABJRU5ErkJggg=="
                                                         alt="{{ $review->user->first_name }} {{ $review->user->last_name }}">
                                                @endisset
                                            </div>
                                            <div class="review-content">
                                                <div class="review-meta">
                                                    <a class="font-weight-bold text-light small" href="{{ route('profile.index', ['user' => $review->user]) }}">
                                                        {{ $review->user->first_name }} {{ $review->user->last_name }}
                                                    </a>
                                                    <small class="d-inline-block ml-2 text-white-50">{{ $review->created_at->diffForHumans() }}</small>
                                                    <div class="rating-group">
                                                        @for ($i = 0; $i < $review->rating; $i++)
                                                            <div class="rating__label"><i class="rating__icon rating__icon--star"> @include('global.partials.icon', ['icon' => 'star'])</i></div>
                                                        @endfor
                                                    </div>
                                                    @if(Auth::user() && Auth::user()->id === $review->user->id)
                                                        <div class="badge align-middle badge-info">You</div>
                                                    @endif
                                                </div>
                                                <div class="review-text mt-1 mb-3"><p class="mb-0">{{ $review->content }}</p>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                @else
                                    <div class="text-center text-white-50 font-italic mb-3 mt-3">No reviews have been made on this product yet</div>
                                @endif
                            </ul>


                        </div>
                        @if(Auth::user() && !App\Models\Review::reviewExists(Auth::user(), $product) &&  Auth::user()->id !== $product->user->id)
                            <div class="review-add px-3 py-3">
                                <h3 class="h6 mb-3 w-100">Leave a review</h3>
                                <div class="review-inner d-flex flex-wrap justify-content-between align-items-middle">
                                    <div class="review-user w-5">
                                        @isset(Auth::user()->profileImage)
                                            <img class="rounded-circle img-fluid img-cover w-45px h-45px" src="{{ asset('storage/' . Auth::user()->profileImage->path)}}"
                                                 alt="{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}">
                                        @else
                                            <img class="rounded-circle img-fluid img-cover w-45px h-45px"
                                                 src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQYV2P48fPXfwAJwwPruND9lQAAAABJRU5ErkJggg=="
                                                 alt="{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}">
                                        @endisset
                                    </div>
                                    <div class="review-form w-95">
                                        <form id="review-form" action="{{ route('review.create', ['product' => $product]) }}" method="POST" class="d-flex m-0 align-items-end">
                                            @csrf
                                            <div class="review-input mr-2 ml-25 w-100">
                                                <label for="rating-1">Choose rating</label><br>
                                                <div class="rating-group mb-2">
                                                    <label aria-label="1 star" class="rating__label" for="rating-1"><i class="rating__icon rating__icon--star"> @include('global.partials.icon', ['icon' => 'star'])</i></label>
                                                    <input class="rating__input" name="rating" id="rating-1" value="1" type="radio" checked>
                                                    <label aria-label="2 stars" class="rating__label" for="rating-2"><i class="rating__icon rating__icon--star"> @include('global.partials.icon', ['icon' => 'star'])</i></label>
                                                    <input class="rating__input" name="rating" id="rating-2" value="2" type="radio">
                                                    <label aria-label="3 stars" class="rating__label" for="rating-3"><i class="rating__icon rating__icon--star"> @include('global.partials.icon', ['icon' => 'star'])</i></label>
                                                    <input class="rating__input" name="rating" id="rating-3" value="3" type="radio">
                                                    <label aria-label="4 stars" class="rating__label" for="rating-4"><i class="rating__icon rating__icon--star"> @include('global.partials.icon', ['icon' => 'star'])</i></label>
                                                    <input class="rating__input" name="rating" id="rating-4" value="4" type="radio">
                                                    <label aria-label="5 stars" class="rating__label" for="rating-5"><i class="rating__icon rating__icon--star"> @include('global.partials.icon', ['icon' => 'star'])</i></label>
                                                    <input class="rating__input" name="rating" id="rating-5" value="5" type="radio">
                                                </div>
                                                <br>

                                                <label for="review-text">Write your review</label>
                                                <input type="text" class="form-control h-50px review-text" id="review-text" name="content" placeholder="Write your review">
                                            </div>
                                            <button type="submit" class="btn h-50px btn-outline-light">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>


        <div class="container mt-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header border-0 pb-0"><h3 class="h6 m-0">Comments</h3></div>
                <div class="card-body">
                    <div class="comments-wrap">
                        <div class="comments-list">

                            <ul class="list-unstyled  @if(!Auth::user()) m-0 @endif">
                                @if($product->comments && count($product->comments) > 0)
                                    @foreach($product->comments as $comment)
                                        <li class="single-comment d-flex flex-wrap" id="comment-{{ $comment->id }}">
                                            <div class="comment-user w-5">
                                                @isset($comment->user->profileImage)
                                                    <img class="rounded-circle border img-fluid img-cover w-45px h-45px" src="{{ asset('storage/' . $comment->user->profileImage->path)}}"
                                                         alt="{{ $comment->user->first_name }} {{ $comment->user->last_name }}">
                                                @else
                                                    <img class="rounded-circle border img-fluid img-cover w-45px h-45px"
                                                         src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQYV2P48fPXfwAJwwPruND9lQAAAABJRU5ErkJggg=="
                                                         alt="{{ $comment->user->first_name }} {{ $comment->user->last_name }}">
                                                @endisset
                                            </div>
                                            <div class="comment-content">
                                                <div class="comment-meta">
                                                    <a class="font-weight-bold small" href="{{ route('profile.index', ['user' => $comment->user]) }}">
                                                        {{ $comment->user->first_name }} {{ $comment->user->last_name }}
                                                    </a>
                                                    <small class="d-inline-block ml-2 text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                                    @if($comment->user->id === $product->user->id)
                                                        <div class="badge align-middle badge-primary">Owner</div>
                                                    @elseif(Auth::user() && Auth::user()->id === $comment->user->id)
                                                        <div class="badge align-middle badge-info">You</div>
                                                    @endif
                                                </div>
                                                <div class="comment-text mt-1 mb-3"><p class="mb-0">{{ $comment->content }}</p>
                                                    @if( Auth::user() && Auth::user()->id === $comment->user->id)
                                                        <a class="text-danger small" id="delete-comment" href="{{ route('comment.delete', ['comment' => $comment]) }}">Delete Comment</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                @else
                                    <div class="text-center text-muted font-italic mb-3 mt-3">No comments have been made on this product yet</div>
                                @endif
                            </ul>


                        </div>
                        @if(Auth::user())
                            <div class="comment-add bg-light px-3 py-3">
                                <h3 class="h6 mb-3 w-100">Leave a comment</h3>
                                <div class="comment-inner d-flex flex-wrap justify-content-between align-items-middle">
                                    <div class="comment-user w-5">
                                        @isset(Auth::user()->profileImage)
                                            <img class="rounded-circle img-fluid img-cover w-45px h-45px" src="{{ asset('storage/' . Auth::user()->profileImage->path)}}"
                                                 alt="{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}">
                                        @else
                                            <img class="rounded-circle img-fluid img-cover w-45px h-45px"
                                                 src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQYV2P48fPXfwAJwwPruND9lQAAAABJRU5ErkJggg=="
                                                 alt="{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}">
                                        @endisset
                                    </div>
                                    <div class="comment-form w-95">
                                        <form id="comment-form" action="{{ route('comment.create', ['product' => $product]) }}" method="POST" class="d-flex m-0">
                                            @csrf
                                            <div class="comment-input mr-2 ml-25 w-100">
                                                <label class="sr-only" for="comment-text">Write your comment</label>
                                                <input type="text" class="form-control h-100 comment-text" id="comment-text" name="content" placeholder="Write your comment">
                                            </div>
                                            <button type="submit" class="btn btn-outline-primary">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>


        <div class="container mt-4 more-seller mb-5">
            <div class="card bg-transparent border-0">
                <div class="card-header border-0 mb-0 px-0 pb-0"><h3 class="h5 m-0">More from this seller</h3></div>
                <div class="card-body px-2 pt-3">
                    <div class="row">
                        <div class="col-12 px-lg-2 mb-3 col-lg-3 col-md-6">
                            <div class="card">
                                <a href="{{ route('profile.index', ['user' => $user]) }}">
                                    @isset($user->profileImage)
                                        <img class="card-img-top img-cover product-user-profile" src="{{ asset('storage/' . $user->profileImage->path)}}"
                                             alt="{{ $user->first_name }} {{ $user->last_name }}">
                                    @else
                                        <img class="card-img-top img-cover product-user-profile"
                                             src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQYV2P48fPXfwAJwwPruND9lQAAAABJRU5ErkJggg=="
                                             alt="{{ $user->first_name }} {{ $user->last_name }}">
                                    @endisset
                                </a>

                                <div class="card-body profile-card--meta">
                                    <h5 class="card-title mb-1 profile-card--title h5"><a class="text-decoration-none" href="{{ route('profile.index', ['user' => $user]) }}">{{ $user->first_name }} {{ $user->last_name }}</a></h5>
                                    <h6 class="small mb-3"><a class="text-muted" href="{{ route('profile.index', ['user' => $user]) }}">{{ '@'. $user->username }}</a></h6>
                                    <p class="card-text ">{{ Str::limit($user->bio, 85) }}</p>
                                    <div class="card-btns d-inline-flex w-100">

                                        @if(Auth::user())
                                            @if($user->isUserFollowing(Auth::user()))
                                                <a href="{{ route('unfollow.user', ['user' =>  $user->username ]) }}" class="btn unfollow-user btn-block mt-0 btn-outline-primary mr-1">Unfollow</a>
                                            @elseif( $user->id !== Auth::user()->id )
                                                <a href="{{ route('follow.user', ['user' =>  $user->username ]) }}" class="btn follow-user btn-block mt-0 btn-primary mr-1">Follow</a>
                                            @endif
                                            <a href="{{ route('profile.index', ['user' => $user]) }}" class="btn btn-outline-primary mt-0 btn-block ml-1">Profile</a>
                                        @else
                                            <a href="{{ route('profile.index', ['user' => $user]) }}" class="btn btn-primary mt-0 btn-block ml-1">View Profile</a>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                        @if($user->products)
                            @php $productCounter = 0; @endphp
                            @foreach($user->products as $userProduct)
                                @if($userProduct->id !== $product->id && $productCounter < 3 && $userProduct->status === 'published')
                                    @include('profile.partials.product_card', ['product' => $userProduct])
                                    @php $productCounter++; @endphp
                                @endif

                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>


        @if($relatedProducts && count($relatedProducts) > 0)
            <div class="container mt-3 more-seller">
                <div class="card bg-transparent border-0">
                    <div class="card-header border-0 mb-0 px-0 pb-0"><h3 class="h5 m-0">Related Products</h3></div>
                    <div class="card-body px-2 pt-3">
                        <div class="row">
                            @foreach($relatedProducts as $relatedProduct)
                                @include('profile.partials.product_card', ['product' => $relatedProduct])
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>



@endsection
