<!DOCTYPE html>
<html>
    <head>
        <title> @yield('title') - Suris - The world of online store</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="{{ asset('packages/king/frontend/css/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ asset('packages/king/frontend/css/font-awesome.css') }}">
        <link rel="stylesheet" href="{{ asset('packages/king/frontend/css/common.css') }}">
        <link rel="stylesheet" href="{{ asset('packages/king/frontend/css/style.css') }}">
    </head>
    <body class="auth-page">

        <div class="_fwfl header">
            <div class="_mw970 _ma _mt15 header-inside">
                @yield('head-link')
            </div>
        </div>

        <div class="_fwfl auth-container">
            <div class="_mw970 _ma">
                @yield('body')
            </div>
        </div>

        <script src="{{ asset('packages/king/frontend/js/jquery_v1.11.1.js') }}"></script>
        <script src="{{ asset('packages/king/frontend/js/bootstrap.js') }}"></script>
        <script src="{{ asset('packages/king/frontend/js/script.js') }}"></script>
    </body>
</html>