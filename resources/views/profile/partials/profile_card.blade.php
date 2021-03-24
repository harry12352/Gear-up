{{-- Profile Card --}}

<div
    class="col-12 profile-data  @if(Auth::check() && Auth::User()->id === $user->id) bg-primary @else bg-secondary @endif">
    <div class="profile-data--wrap d-flex align-items-md-end align-items-center flex-md-nowrap flex-wrap">

        @isset($user->profileImage)
            <div class="profile-image mt-md-4 mt-3">
                <img src="{{ asset('storage/' . $user->profileImage->path)}}"
                     alt="{{ $user->first_name }} {{ $user->last_name }}">
            </div>
        @else
            <div class="profile-image mt-md-4 mt-3">
                <img width="200" height="200"
                     src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQYV2P48fPXfwAJwwPruND9lQAAAABJRU5ErkJggg=="
                     alt="{{ $user->first_name }} {{ $user->last_name }}">
            </div>
        @endisset
        <div class="profile-name">
            <h1 class="h4 profile-name-title m-0">{{ $user->first_name }} {{ $user->last_name }}</h1>
            <div class="profile-name-username mb-1">{{ '@' . $user->username }}</div>
        </div>

        <div class="profile-action d-block d-md-none">
            @if(Auth::check() && Auth::User()->id === $user->id)
                <a href="{{ route('settings.account') }}" class="btn btn-outline-light btn-sm">Edit profile</a>
            @else
                @if(Auth::check())
                    @if(isUserFollowing($user))
                        <a href="{{ route('unfollow.user', ['user' =>  $user->username ]) }}"
                           class="btn btn-secondary unfollow-user">Unfollow</a>
                    @else
                        <a href="{{ route('follow.user', ['user' =>  $user->username ]) }}"
                           class="btn btn-primary follow-user">Follow</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">Follow</a>
                @endif
            @endguest
        </div>

        <div
            class="profile-meta pl-md-5 pl-0 mb-1 d-flex align-items-end justify-content-md-start justify-content-around">
            <div class="profile-products mr-md-3 mr-lg-5">
                <div class="profile-meta--title">Products</div>
                <div class="profile-meta--value">
                    @if($user->products)
                        {{ $user->products->count() }}
                    @else
                        0
                    @endif
                </div>
            </div>
            <div class="profile-following mr-md-3 mr-lg-5">
                <div class="profile-meta--title">Following</div>
                <div class="profile-meta--value">
                    @if($user->following)
                        {{ $user->following->count() }}
                    @else
                        0
                    @endif
                </div>
            </div>
            <div class="profile-followers mr-md-3 mr-lg-5">
                <div class="profile-meta--title">Followers</div>
                <div class="profile-meta--value">
                    @if($user->followers)
                        {{ $user->followers->count() }}
                    @else
                        0
                    @endif
                </div>
            </div>
            <div class="profile-likes">
                <div class="profile-meta--title">Likes</div>
                <div class="profile-meta--value">
                    @if($user->likes)
                        {{ $user->likes->count() }}
                    @else
                        0
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>


{{-- Profile Tabs --}}
<div class="col-12 profile-tabs pt-3 d-none d-md-block">
    <div class="profile-tabs--wrap d-flex justify-content-between">

        @include('profile.partials.profile_nav', ["tab" => $tab])

        <div class="profile-action">
            @if(Auth::check() && Auth::User()->id === $user->id)
                <a href="{{ route('settings.account') }}" class="btn btn-outline-secondary">Edit profile</a>
            @else
                @if(Auth::check())
                    @if(isUserFollowing($user))
                        <a href="{{ route('unfollow.user', ['user' =>  $user->username ]) }}"
                           class="btn btn-secondary unfollow-user">Unfollow</a>
                    @else
                        <a href="{{ route('follow.user', ['user' =>  $user->username ]) }}"
                           class="btn btn-primary follow-user">Follow</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">Follow</a>
                @endif
            @endguest
        </div>

    </div>
</div>
