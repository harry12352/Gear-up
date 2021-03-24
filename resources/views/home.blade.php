@extends('layouts.app')

@section('content')

    @guest
        @include('home.landing')
    @else
        @include('home.feed')
    @endguest
@endsection

