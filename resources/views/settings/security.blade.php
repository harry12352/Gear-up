@extends('layouts.app', ["title" => "Security Settings"])

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class='col-sm-10'>
                <div class="settings-title mb-4">
                    <h1 class="h4">Settings</h1>
                </div>

                @include('settings.settings_nav', ["tab" => "password"])
                <div class="tab-content settings-tab-content py-3 px-3 px-sm-0">
                    <div class="tab-pane show active p-1">

                        <div class="row">
                            <div class='col-sm-7'>


                                <form action='{{ route('settings.security') }}' method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="old_password"> {{ __('Old Password') }} </label>
                                        <input type="password" class="form-control  @error('old_password') is-invalid @enderror" id="old_password" name="old_password"/>
                                        <small class="form-text text-muted">Leave blank to leave unchanged</small>
                                        @error('old_password')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                    </div>

                                    <div class="form-row row">
                                        <div class="form-group col-md-6">
                                            <label for="password-confirm">{{ __('New Password') }}</label>
                                            <input type="password" class="form-control validate-input @error('password') is-invalid @enderror" name="password" id="password" data-regex="(?=.*?[0-9])(?=.*?[#?!@$%^&*-])" data-regex-message="Password must contain at least one number and one special character (@$!%*#?&)" min="8" >
                                            @error('password')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="new_password">{{ __('Confirm New Password') }}</label>
                                            <input type="password" class="form-control validate-input @error('password_confirmation') is-invalid @enderror" name="password_confirmation" id="password-confirm" data-sameInput="password">
                                            @error('password_confirmation')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                        </div>
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
