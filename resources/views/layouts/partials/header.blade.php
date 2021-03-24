<header>

    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="/">
            <img src="{{ asset('assets/gearwrx.png') }}">
        </a>
        <form class="form-inline ml-4 my-2 my-lg-0 search_form" action="{{route('products.search')}}" method="GET">
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <svg class="search_icon" width="18" height="18" xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 32 32">
                            <path
                                d="M 19 3 C 13.488281 3 9 7.488281 9 13 C 9 15.394531 9.839844 17.589844 11.25 19.3125 L 3.28125 27.28125 L 4.71875 28.71875 L 12.6875 20.75 C 14.410156 22.160156 16.605469 23 19 23 C 24.511719 23 29 18.511719 29 13 C 29 7.488281 24.511719 3 19 3 Z M 19 5 C 23.429688 5 27 8.570313 27 13 C 27 17.429688 23.429688 21 19 21 C 14.570313 21 11 17.429688 11 13 C 11 8.570313 14.570313 5 19 5 Z"/>
                        </svg>
                    </div>
                </div>
                <input type="text" class="form-control" id="search_input" placeholder="Search" name="q" value="@if( request()->get('q') ){{request()->get('q')}}@endif">
            </div>

        </form>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">@include('global.partials.icon', ['icon' => 'menu'])</span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">


                <li class="nav-item">
                    <a class="nav-link {{ Request::is('products') ? 'active' : '' }}" href=" {{ route('products.all') }}">
                        @include('global.partials.icon', ['icon' => 'explore'])
                        Explore
                    </a>
                </li>

                @guest

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            @include('global.partials.icon', ['icon' => 'user'])
                            Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary" href="{{ route('register') }}">Sign-up</a>
                    </li>

                @else

                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('/') ? 'active' : '' }} {{ Request::is('home') ? 'active' : '' }}" href=" {{ route('home') }}">
                            @include('global.partials.icon', ['icon' => 'feed'])
                            Feed</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('profile/*/products') ? 'active' : '' }}" href="{{ route('products.index', ['user' => Auth::user()->username]) }}">
                            @include('global.partials.icon', ['icon' => 'closet'])
                            My Products</a>
                    </li>
                    <li class="nav-item user-notification dropdown">
                        <a class="nav-link dropdown-toggle" id="notificationsDropdown" href="" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @include('global.partials.icon', ['icon' => 'notification'])
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notificationsDropdown" data-notificationPage="1">
                            <div class="dropdown-item notification dropdown-item--simple">
                                <div class="d-flex justify-content-between">
                                    <p class="notification--header">Notifications</p>
                                </div>
                            </div>
                            <div class="dropdown-item notification dropdown-item--simple">
                                <div class="d-flex justify-content-center">
                                    <p class="text-muted"><i>You don't have any notifications at the moment.</i></p>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item user-profile dropdown">
                        <a class="nav-link dropdown-toggle @empty(Auth::user()->profileImage->path) no-image @endempty"
                           id="profileDropdown" href="" role="button" data-toggle="dropdown" aria-haspopup="true"
                           aria-expanded="false">
                            @isset(Auth::user()->profileImage)
                                <img class="h-35px w-35px"
                                     src="{{ asset('storage/' . Auth::user()->profileImage->path) }}">
                            @else
                                <img width="30" height="30"
                                     src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQYV2P48fPXfwAJwwPruND9lQAAAABJRU5ErkJggg=="
                                     alt="{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}">
                            @endisset
                            <span class="user-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                            <span class="sr-only" id="headerusername">{{ Auth::user()->username }}</span>
                            @include('global.partials.icon', ['icon' => 'chevron_down'])
                        </a>
                        <div class="dropdown-menu" aria-labelledby="profileDropdown">
                            <a class="dropdown-item" href="{{ route('products.index', ['user' => Auth::user()->username]) }}">My Products</a>
                            <a class="dropdown-item" href="{{ route('offers.myoffers', ['user' => Auth::user()]) }}">My Offers</a>
                            <a class="dropdown-item" id="userProfileUrl"
                               href="{{ route('profile.index', ['user' =>  Auth::user()->username ]) }}">Profile</a>
                            <a class="dropdown-item" href="{{ route('settings.account') }}">Settings</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                  style="display: none;">@csrf</form>
                        </div>
                    </li>

                @endguest

            </ul>
        </div>
    </nav>


</header>
