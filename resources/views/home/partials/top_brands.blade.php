<div class="card shadow-sm">
    <div class="card-header">
        <h2 class="h6 m-0">Top Brands to follow</h2>
    </div>
    <div class="card-body pb-2 pt-3">
        <div class="d-flex overflow-auto mini-scroll pb-2 top-brands" data-brand-url="{{ route('top.brands') }}">

            @for ($i = 0; $i < 5; $i++)
                <div class="brand-card mr-3 user-select-none">
                    <div class="d-flex justify-content-between align-items-center animation-pulse">
                        <div class="brand-image-wrapper">
                            <a class="text-decoration-none" href="{{ route('home') }}">
                                <img class="img-fluid rounded-circle" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQYV2P48uXrfwAJmgPd+J22fAAAAABJRU5ErkJggg==">
                            </a>
                        </div>
                        <div class="brand-card--meta pl-3">
                            <div class="p-2 mb-1 bg-light rounded-sm"></div>
                            <div class="p-1 bg-light rounded-sm mb-2"></div>
                            <div class="p-3 w-75 bg-light rounded-sm"></div>
                        </div>
                    </div>
                </div>
            @endfor


        </div>
    </div>
</div>
