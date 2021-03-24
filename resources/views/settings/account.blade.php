@extends('layouts.app', ["title" => "Account Settings"])

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class='col-sm-10'>
                <div class="settings-title mb-4">
                    <h1 class="h4">Settings</h1>
                </div>

                @include('settings.settings_nav', ["tab" => "account"])
                <div class="tab-content settings-tab-content py-3 px-3 px-sm-0">
                    <div class="tab-pane show active p-1">

                        <div class="row">
                            <div class='col-sm-7'>

                                <form action='{{ route('settings.account') }}' method="POST" enctype="multipart/form-data" id="account-form">
                                    @csrf
                                    <div class="form-group">
                                        @isset(Auth::user()->profileImage)
                                            <img class="rounded-circle account-profile-image" onerror="this.style.display = 'none'" src="{{ asset('storage/' . Auth::user()->profileImage->path) }}"/>
                                            <a href="javascript:void(0)" onclick="event.preventDefault();document.getElementById('profile_image').click();" class="text-decoration-underline d-block">Change profile image</a>
                                            <input style="display:none;" onchange="event.preventDefault();document.getElementById('account-form').submit();" type="file" class="form-control-file" name="profile_image" id="profile_image" aria-describedby="profile_image">
                                        @else
                                            <a href="javascript:void(0)" onclick="event.preventDefault();document.getElementById('profile_image').click();" class="text-decoration-underline d-block">Add profile image</a>
                                            <input style="display:none;" onchange="event.preventDefault();document.getElementById('account-form').submit();" type="file" class="form-control-file" name="profile_image" id="profile_image" aria-describedby="profile_image">
                                        @endisset
                                        @error('profile_image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-row row">
                                        <div class="form-group col-md-6">
                                            <label for="first_name">{{ __('First Name') }}</label>
                                            <input id="first_name" name="first_name" type="text" required class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $user->first_name ?? '') }}">

                                            @error('first_name')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="last_name">{{ __('Last Name') }}</label>
                                            <input type="text" required class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $user->last_name ?? '') }}" name="last_name" id="last_name">

                                            @error('last_name')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="username">{{ __('Username') }}</label>
                                        <input type="text" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $user->username ?? '') }}" name="username" id="username" readonly disabled>

                                        @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <small class="text-muted form-text">Username cannot be changed.</small>
                                    </div>
                                    <div class="form-row row">
                                        <div class="form-group col-md-6">
                                            <label for="email">{{ __('Email') }}</label>
                                            <input type="email" required class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email ?? '') }}" name="email" id="email">

                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="phone">{{ __('Phone') }}</label>
                                            <input type="phone" class="form-control  @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone ?? '') }}" name="phone" id="phone">

                                            @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="bio">{{ __('Bio') }}</label>
                                        <textarea class="form-control  @error('bio') is-invalid @enderror" id="bio" name="bio" rows="3">{{ old('bio', $user->bio ?? '' ) }}</textarea>

                                        @error('bio')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


@endsection
