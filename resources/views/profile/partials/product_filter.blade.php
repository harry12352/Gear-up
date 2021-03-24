<div class="card product-filter @if(!isset($user)) mt-n6 @endif mt-4 mb-5 border-0 shadow-sm">
    <div class="card-header border-bottom-0 d-flex justify-content-between pb-0">
            <h2 class="h6 m-0">Filter Products</h2>
            <a class="product-filter--expand" href="#">Expand</a>
    </div>
    <div class="card-body pt-2">
        <div class="active-filters mb-2 pb-1"></div>
        <form action="{{ route('filter.products') }}">
            @if(Auth::user())
                <input type="hidden" class="userId" value="{{ Auth::user()->id }}">
            @endif
            <div class="row">
                <div class="col-md-3">
                    <label for="filter-brand" class="sr-only">Filter by Brands</label>
                    <select data-name="Brands" name="brand" id="filter-brand" multiple>
                        <option value="" selected>All</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filter-categories" class="sr-only">Filter by Categories</label>
                    <select data-name="Category" name="category" id="filter-categories">
                        <option value="" selected>All</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filter-colors" class="sr-only">Filter by Colors</label>
                    <select data-name="Colors" name="color" id="filter-colors" multiple>
                        <option value="" selected>All</option>
                        @foreach($colors as $color)
                            <option value="{{ $color->id }}">{{ $color->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filter-sizes" class="sr-only">Filter by Sizes</label>
                    <select data-name="Sizes" name="size" id="filter-sizes" multiple>
                        <option value="" selected>All</option>
                        @foreach($sizes as $size)
                            <option value="{{ $size->id }}">{{ $size->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mt-4">
                    <label for="filter-prices" class="sr-only">Filter by Price</label>
                    <div class="filter-prices">
                        <div class="multi-rangeSlider">
                            <input type="range" id="input-left" name="price_min" min="{{ $min_price }}" max="{{ $max_price }}" value="{{ $min_price }}">
                            <input type="range" id="input-right" name="price_max" min="{{ $min_price }}" max="{{ $max_price }}" value="{{ $max_price }}">
                            <div class="multi-rangeSlider--custom">
                                <div class="multi-rangeSlider--custom__track"></div>
                                <div class="multi-rangeSlider--custom__range"></div>
                                <div class="multi-rangeSlider--custom__thumb left"></div>
                                <div class="multi-rangeSlider--custom__thumb right"></div>
                            </div>
                        </div>
                        <div class="filter-prices--value">
                            <div class="filter-prices--value__min">${{ $min_price }}</div>
                            <div class="filter-prices--value__max">${{ $max_price }}</div>
                        </div>
                    </div>
                </div>
                @if(isset($user))
                    <input type="hidden" name="username" value="{{ $user->username }}">
                @endif
            </div>
        </form>
    </div>
</div>
