@extends('layouts.app')

@section('content')
    <div class="row no-gutters justify-content-start align-items-center vh-100">
        <div class="col-lg-6">

            <div class="auth-wrap ml-4 ml-xl-6">

                <a href="/"><img class="logo position-absolute" src="{{ asset('/assets/gearwrx.png') }}"></a>

                <div class="auth-header mb-4">
                    <h1 class="h4">{{__('Welcome back')}}</h1>
                    <p class="lead">{{__('Login to your '. config('app.name', 'Gear-up') .' account to continue buying or selling active gear products')}}</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <label for="username_email">Email or Username</label>
                        <input type="text" name="username_email" class="form-control @error('username') is-invalid @enderror @error('username_email') is-invalid @enderror @error('email') is-invalid @enderror" id="username_email" value="{{ old('username_email') }}" required autofocus>
                        @error('username_email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" required>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <p class="text-muted"><a href="{{ route('password.request') }}">Forgot your password?</a></p>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                    <p class="text-muted mt-2">Don't have an account? <a href="{{ route('register') }}">Sign-up</a></p>
                </form>
            </div>

        </div>
        <div class="col-lg-6 auth-cover-col">
            <img class="w-100 vh-100 auth-cover" src="{{ asset('/assets/login.jpg') }}" alt="">
        </div>
    </div>
@endsection
