@extends('layouts.app', ["title" => "Delete Account"])

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class='col-sm-10'>
                <div class="settings-title mb-4">
                    <h1 class="h4">Settings</h1>
                </div>

                @include('settings.settings_nav', ["tab" => "delete"])
                <div class="tab-content settings-tab-content py-3 px-3 px-sm-0">
                    <div class="tab-pane show active p-1">

                        <div class="row">
                            <div class='col-sm-7'>

                                <form action='{{ route('settings.delete') }}' method="post">
                                    @csrf
                                    <p>If you delete your {{ config('app.name', 'Gearwrx') }} account, you will no longer have access to the account and all information will be permanently deleted.</p>
                                    <p>Please note that {{ config('app.name', 'Gearwrx') }} may retain certain information after account deletion as required or permitted by the law.</p>
                                    <button type="submit" class="btn btn-danger">Delete Account</button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


@endsection
