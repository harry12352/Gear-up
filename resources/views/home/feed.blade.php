<div class="container">
    <div class="row brands-wrapper d-none">
        <div class="col">
            @include('home.partials.top_brands')
        </div>
    </div>

    <div class="row content-wrapper mt-4">

        <div class="d-none feed-sidebar d-md-block col-md-3 col-lg-3">
            @include('home.partials.popular_followers')
            @include('home.partials.recently_viewed')
        </div>
        <div class="col-md-6 col-lg-6">
            <div class="pb-2 home-feed" data-feedPage="1" data-feed-url="{{ route('feed') }}">
                <div class="empty_feed d-none">
                    <div class="text-center text-muted font-italic mb-3">Nothing to show here</div>
                    <div class="alert alert-info">
                        Follow some people to grow your circle and you will see their products here by time
                    </div>
                </div>
                @for ($i = 0; $i < 5; $i++)
                    <div class="product_feed_card card animation-pulse mb-3 shadow-sm">
                        <div class="card-body">
                            <div class="p-8 w-100 bg-light rounded-sm"></div>
                            <div class="p-8 w-100 bg-light rounded-sm"></div>
                            <div class="p-8 w-100 bg-light rounded-sm"></div>
                            <div class="p-8 w-100 mb-2 bg-light rounded-sm"></div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="p-2 w-25 mb-1 bg-light rounded-sm"></div>
                                    <div class="p-1 w-15 bg-light rounded-sm mb-2"></div>
                                    <div class="p-2 w-20 bg-light rounded-sm mb-2 mt-2"></div>
                                </div>
                                <div class="col-6 text-right">
                                    <div class="p-2 w-20 mb-3 bg-light rounded-sm d-inline-block"></div>
                                    <div class="p-2 w-20 mb-3 bg-light rounded-sm d-inline-block"></div>
                                    <div class="p-2 w-20 mb-3 bg-light rounded-sm d-inline-block"></div>
                                    <div class="row">
                                        <div class="col-9">
                                            <div class="p-1 w-25 mb-3 bg-light rounded-sm ml-auto"></div>
                                        </div>
                                        <div class="col-3">
                                            <div class="p-1 w-100 mb-3 bg-light rounded-sm ml-auto"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor

            </div>
        </div>
        <div class="d-none feed-sidebar d-md-block col-md-3 col-lg-3">
            @include('home.partials.people_you_may_know')
        </div>
    </div>
</div>
