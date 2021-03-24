@extends('layouts.app')

@section('content')
    <div class="row no-gutters justify-content-start align-items-center vh-100">
        <div class="col-lg-6">

            <div class="auth-wrap ml-4 ml-xl-6">

                <a href="/"><img class="logo position-absolute" src="{{ asset('/assets/gearwrx.png') }}"></a>

                <div class="auth-header mb-4">
                    <h1 class="h4">{{__('Create an account')}}</h1>
                    <p class="lead">{{__('By creating account you will be joining millions of people on the largest marketplace for outdoor gear')}}</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf



                    <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="first_name">{{ __('First Name') }}</label>
                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required autofocus>
                        @error('first_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="last_name">{{ __('Last Name') }}</label>
                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                        @error('last_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    </div>

                    <div class="form-group">
                        <label for="username">{{ __('Username') }}</label>
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" required>
                        @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <small class="form-text text-muted">{{__('Choose something you like, this cannot be changed')}}</small>
                    </div>

                    <div class="form-group">
                        <label for="email">{{ __('E-Mail') }}</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">{{ __('Password') }}</label>
                        <input id="password" data-regex="(?=.*?[0-9])(?=.*?[#?!@$%^&*-])" data-regex-message="Password must contain at least one number and one special character (@$!%*#?&)" min="8" type="password" name="password" class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}" required>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <small class="form-text text-muted">{{__('Must be at least 8 characters, one number and one special character')}}</small>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                        <input data-sameInput="password" type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" value="{{ old('password_confirmation') }}" min="8" required>
                        @error('password_confirmation')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>



                    <button type="submit" class="btn btn-primary btn-block">Register</button>
                    <p class="text-muted mt-2">Already have an account? <a href="{{ route('login') }}">Login</a></p>
                </form>
            </div>

        </div>
        <div class="col-lg-6 auth-cover-col">
            <img class="w-100 vh-100 auth-cover" src="{{ asset('/assets/register.jpg') }}" alt="">
        </div>
    </div>
@endsection
