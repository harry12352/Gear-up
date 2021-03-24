<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="user-profile-card">
                <div class="row">
                    @include('profile.partials.profile_card', ['tab' => 'products'])
                </div>
            </div>
            @include('profile.partials.product_filter')
            <div class="profile-data profile-products mt-4">
                @if($products && count($products) > 0)
                    <div class="container p-0">
                        <div class="row match-height filtered_products mx-1">
                            @if(Auth::id() === $user->id && !(isset($_GET['page']) && (int)$_GET['page'] > 1) )
                                @include('profile.partials.new_product_card')
                            @endif
                            @foreach($products as $product)
                                @include('profile.partials.product_card')
                            @endforeach
                        </div>
                        <div>
                            {{ $products->links() }}
                        </div>
                    </div>
                @else
                    <div class="empty-data text-center">

                        @if(Auth::check() && Auth::User()->id === $user->id)
                            <div class="container p-0">
                                <div class="row match-height" class="mx-1">
                                    @include('profile.partials.new_product_card')
                                </div>
                            </div>
                        @else
                            <h5 class="mt-4 font-weight-normal">No products found</h5>
                        @endif
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
