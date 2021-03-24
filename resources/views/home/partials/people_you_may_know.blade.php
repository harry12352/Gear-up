<div class="card people-know-wrapper d-none">
    <div class="card-header">
        <h3 class="h6 m-0">People you may know</h3>
    </div>
    <div class="card-body pb-2 pt-3">
        <div class="d-flex flex-wrap people-know" data-people-url="{{ route('people.follow') }}">

            @for ($i = 0; $i < 6; $i++)
                <div class="brand-card @if($i !== 5) mb-4 @endif user-select-none w-75">
                    <div class="d-flex justify-content-between align-items-center animation-pulse">
                        <div class="brand-image-wrapper">
                            <a class="text-decoration-none" href="{{ route('home') }}">
                                <img class="img-fluid rounded-circle" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQYV2P48uXrfwAJmgPd+J22fAAAAABJRU5ErkJggg==" alt="{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}">
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
