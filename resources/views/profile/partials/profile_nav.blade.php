<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <a class="nav-item nav-link @if(isset($tab) && $tab == "products") active @endif" href="{{ route('products.index', ['user' => $user->username]) }}">
            {{ __('Products') }}
        </a>
        <a class="nav-item nav-link @if(isset($tab) && $tab == "following") active @endif" href="{{ route('profile.following', ['user' => $user->username]) }}">
            {{ __('Following') }}
        </a>
        <a class="nav-item nav-link @if(isset($tab) && $tab == "followers") active @endif" href="{{ route('profile.followers', ['user' => $user->username]) }}">
            {{ __('Followers') }}
        </a>
        <a class="nav-item nav-link @if(isset($tab) && $tab == "likes") active @endif" href="{{ route('profile.likes', ['user' => $user->username]) }}">
            {{ __('Likes') }}
        </a>
    </div>
</nav>
