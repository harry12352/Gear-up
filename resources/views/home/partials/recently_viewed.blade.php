<div class="card recently-viewed-wrapper d-none">
    <div class="card-header">
        <h3 class="h6 m-0">Recently viewed products</h3>
    </div>
    <div class="card-body pb-2 pt-3">
        <div class="d-flex flex-wrap recently-viewed" data-recentlyViewed-url="{{ route('retrieve.products') }}">

            @for ($i = 0; $i < 3; $i++)
                <div class="product_feed_card w-100 card border-0 @if($i !== 2) mb-4 @endif  animation-pulse mb-3">
                    <div class="p-6 pb-8 w-100 mb-2 bg-light rounded-sm"></div>
                    <div class="p-2 w-40 mb-3 bg-light rounded-sm d-inline-block"></div>
                </div>
            @endfor


        </div>
    </div>
</div>
