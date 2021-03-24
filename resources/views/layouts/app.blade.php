<!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('layouts.partials.head')

    <body class="{{ bodyClass() }} @isset($bodyClass){{ $bodyClass }}@endif">

        {{--  Login/Register pages don't need header  --}}
        @if (bodyClass() != "login" && bodyClass() != "register")
            @include('layouts.partials.header')
        @endif

        <main>
            @if (bodyClass() != "login" && bodyClass() != "register")
                {!! getFlashMessages() !!}
            @endif
            @yield('content')
        </main>

        @include('layouts.partials.footer')

    </body>

</html>
