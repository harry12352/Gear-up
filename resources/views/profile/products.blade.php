@extends('layouts.app', ['title' => $user->first_name . ' ' .$user->last_name])
@section('content')
    @include('profile.products.index')
@endsection
