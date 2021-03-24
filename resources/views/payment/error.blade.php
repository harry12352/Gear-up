@extends('layouts.app', ['title' => 'Order Successful'])
@section('content')
    <div class="container payment-failed pb-5">

        <div class="shadow rounded bg-white py-5">
                <div class="w-80px mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2048 2048"><path fill="#D0021B" d="M1024 0q141 0 272 36.5T1540.5 140t207 160.5 160.5 207T2011.5 752t36.5 272-36.5 272-103.5 244.5-160.5 207-207 160.5-244.5 103.5-272 36.5-272-36.5T507.5 1908t-207-160.5-160.5-207T36.5 1296 0 1024t36.5-272T140 507.5t160.5-207 207-160.5T752 36.5 1024 0zm113 1024l342-342-113-113-342 342-342-342-113 113 342 342-342 342 113 113 342-342 342 342 113-113z"/></svg>
                </div>
                <h1 class="text-center font-weight-light">{{ __('Something went wrong') }}</h1>
                <h3 class="text-center font-weight-normal h5 mt-2">{{ __('Your order could not be placed. There was an error while processing your payment.') }}</h3>
                <div class="d-flex failed-note mx-auto mt-5">
                    <div class="w-90px mr-3">@include('global.partials.icon', ['icon' => 'warning'])</div>
                    <p>{{ __('Please double check your payment details and try again. If the problem persists, please contact us for support') }}</p>
                </div>
            <div class="text-center mt-4">
                <a href="{{ route('feed') }}" class="btn btn-primary">Back to Home</a><br>
                <a href="/support" class="d-inline-block mt-3 contact-support"> <span class="d-inline-block w-20px">@include('global.partials.icon', ['icon' => 'chat'])</span> Contact Us</a>
            </div>
        </div>
    </div>
@endsection
