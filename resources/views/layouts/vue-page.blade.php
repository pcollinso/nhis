<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="current-user-name" content="{{ $currentUserName }}">
        <meta name="current-user-type" content="{{ $currentUserType }}">
        <meta name="permissions" content="{{ json_encode($currentPermissions) }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">

        <title>NHIS | {{ $pageTitle }}</title>

        <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div class="flex-center position-ref full-height">

            <div id="app">
              @yield('content')
            </div>
        </div>
        <script type="text/javascript" src="{{ asset('js/manifest.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/vendor.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
