<div class="col-12 col-md-4 col-lg-3 px-lg-2  mb-3">
    <div class="card product-card h-100 position-relative">
        @if(Auth::id() == $user->id)
            <a href="{{ route('products.edit', ['user' => $user, 'id' => $product->id]) }}" class="position-absolute product-card--edit">
                @include('global.partials.icon', ['icon' => 'edit'])
            </a>
        @endif
        @if(!$product->files->isEmpty() && (count($product->files) > 0))
            <a href="{{route('products.show',['user'=>$user->username,'id' => $product->id])}}" class="text-decoration-none">
                <div class="product-card--image">
                    <img src="{{ Storage::url(@($product->files()->whereResourceName('product')->first()->path)) }}" class="card-img-top" alt="{{ $product->name }}">
                </div>
            </a>
        @else
            <a href="{{route('products.show',['user'=>$user->username,'id' => $product->id])}}" class="text-decoration-none">
                <div class="product-card--image">
                    <img
                        src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQYV2P48fPXfwAJwwPruND9lQAAAABJRU5ErkJggg=="
                        alt="{{ $product->name }}">
                </div>
            </a>

        @endif

        <div class="card-body p-3">
            <h5 class="card-title product-card--title"><a href="{{route('products.show',['user'=>$user->username,'id' => $product->id])}}" class="text-decoration-none">{{$product->title}}</a></h5>
            <p class="product-card--price font-weight-bold">${{ number_format($product->price, 2) }}</p>

            <p class="product-card--meta m-0 pt-3 border-top">

                @if($product->status === 'drafted')
                    <span class="d-block alert alert-info w-100 m-0 text-center border border-info">This product is in draft</span>
                @else
                    @if($product->hasUserLiked(Auth::user()))
                        <a href="{{ route('product.unlike', ['product' => $product ]) }}" class="unlike-product btn btn-light btn-sm">
                            @include('global.partials.icon', ['icon' => 'unlike'])
                            Liked
                        </a>
                    @else
                        <a href="{{ route('product.like', ['product' => $product ]) }}" class="like-product btn btn-light btn-sm">
                            @include('global.partials.icon', ['icon' => 'like'])
                            Like
                        </a>
                    @endif


                    <a href="{{ route('product.share', ['product' => $product ]) }}" class="share-product float-right btn btn-light btn-sm">
                        @include('global.partials.icon', ['icon' => 'share'])
                        Share
                    </a>
                @endif



            </p>
        </div>
    </div>
</div>
