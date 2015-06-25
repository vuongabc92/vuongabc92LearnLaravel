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
    <body>
        <div class="_fwfl _bgw header">
            <div class="_mw970 _ma">
                <div class="_fwfl _mt10 header-inside">
                    <div class="_fl head-left-col">
                        a
                    </div>
                    <div class="_fr head-right-col">
                        <div class="_fl head-search-location">
                            <form class="_fl _fwb _fs13 header-search-form">
                                <input type="text" class="_fwfl _fh _ff0 _r2 header-search-input" placeholder="{{ _t('head-search-placeholder') }}">
                                <button type="submit" class="_ff0 _fl _fh _fs17 _tb header-search-btn"><i class="fa fa-search"></i></button>
                            </form>
                            <span class="btn _btn _btn-blue _r2 head-location-btn">
                                <i class="glyphicon glyphicon-map-marker"></i> 
                                Ho Chi Minh 
                                <i class="caret"></i>
                            </span>
                        </div>
                        <div class="_fr">
                            @if (auth()->check())
                            <img src="{{ get_avatar() }}" style="width:34px;"/>
                            @else
                                <a href="{{ route('front_register') }}" class="btn _btn _btn-white _r2 _fr head-signup">{{ _t('signup') }}</a>
                                <a href="{{ route('front_login') }}" class="btn _btn _btn-white _r2 _fr head-signin">{{ _t('signin') }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ asset('packages/king/frontend/js/jquery_v1.11.1.js') }}"></script>
        <script src="{{ asset('packages/king/frontend/js/bootstrap.js') }}"></script>
        <script src="{{ asset('packages/king/frontend/js/script.js') }}"></script>
    </body>
</html>
