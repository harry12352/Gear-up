<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@isset($title){{ $title }} | @endisset{{ config('app.name', 'Gear-up') }}</title>


    <!-- Styles -->
    <link rel="preload" href="{{ asset('css/app.css') }}" as="style">
    <link rel="preload" href="https://fonts.googleapis.com/css?family=Lato:400,700,900|Roboto:300,400,500,700&amp;display=swap" as="style">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&amp;display=swap" type="text/css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script>
        const SITE_URL = '{{ env('APP_URL') }}';
        const SITE_ASSET_URL = '{{ asset('/') }}';
        const SITE_STORAGE_URL = '{{ asset('storage/') }}';
    </script>
</head>
