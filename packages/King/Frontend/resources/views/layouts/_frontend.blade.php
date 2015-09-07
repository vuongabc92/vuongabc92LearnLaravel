<!DOCTYPE html>
<html>
    <head>
        <title> @yield('title') - {{ config('front.site_name') }} - {{ config('front.site_desc') }}</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="{{ asset('packages/king/frontend/css/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ asset('packages/king/frontend/css/font-awesome.css') }}">
        @yield('head_css')
        <link rel="stylesheet" href="{{ asset('packages/king/frontend/css/common.css') }}">
        <link rel="stylesheet" href="{{ asset('packages/king/frontend/css/style.css') }}">
    </head>
    <body>
        <div class="_fwfl _bgw header">
            <div class="_mw970 _ma">
                <div class="_fwfl _mt10 header-inside">
                    <div class="_fl head-left-col">
                        <a class="_fl head-logo-nav"></a>
                    </div>
                    <div class="_fr head-right-col">
                        <div class="_fl head-search-location">
                            <div class="_fwfl head-search-inside">
                                <form class="_fl _fwb _fs13 header-search-form">
                                    <input type="text" class="_fwfl _fh _ff0 _r2 header-search-input" placeholder="{{ _t('head-search-placeholder') }}">
                                    <button type="submit" class="_ff0 _fl _fh _fs17 _tb header-search-btn"><i class="fa fa-search"></i></button>
                                </form>

                                <div class="btn-group location-btn-group location-dropdown">
                                    <button type="button" class="btn _btn _btn-blue head-location-btn" data-toggle="dropdown">
                                        <i class="glyphicon glyphicon-map-marker"></i>
                                        <span id="current-location-name">{{ current_location()->name }}</span>
                                        <i class="caret"></i>
                                    </button>
                                    <div class="_r2 dropdown-menu header-location-dropdown" role="">
                                        <div class="_fwfl popup-header location-popup-header" onclick="return false;">
                                            {!! Form::open(['route' => 'front_search_location', 'method' => 'POST', 'class' => '_fwfl search-location-form', 'id' => 'search-location-form', 'data-search-location' => '#list-location']) !!}
                                                {!! Form::text('location_keyword', '', ['class' => '_fwfl _r2 _ff0', 'placeholder' => _t('search_location'), 'data-event-trigger' => '#search-location-form', 'data-event' => 'keyup|submit', 'autocomplete' => 'off']) !!}
                                                <button type="submit" class="_ff0 _fl _fh _fs14 location-search-btn"><i class="fa fa-search"></i></button>
                                            {!! Form::close() !!}
                                        </div>
                                        <div class="_fwfl popup-body">
                                            <ul class="_fwfl _ls list-location" id="list-location" data-select-location="{{ route('front_select_location', 0) }}" data-from-to-target=".popup-body|#current-location-name">
                                                @if (count(locations()))
                                                    @foreach(locations() as $one)
                                                        <li data-value="{{ $one->id }}">
                                                            <span class="_fl location-name">{{ $one->name }}</span>
                                                            <span class="_fr">{{ $one->count_store }}</span>
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <span class="_fr glyphicon glyphicon-map-marker _tb _fs20 _mt6 _cp mobile-head-location"></span>
                            </div>
                        </div>
                        <div class="_fr head-right-nav">
                            @if (auth()->check())
                            <div class="_fr btn-group avatar-dropdown">
                                <button type="button" class="btn dropdown-toggle _bgw _p0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img src="{{ get_avatar('small') }}" class="_fl img-circle head-avatar-img avatar-small"/>
                                </button>
                                <ul class="dropdown-menu _r2">
                                    <li>
                                        <a href="#" class="short-profile-nav">
                                            <span class="_fwfl">
                                                <img src="{{ get_avatar('medium') }}" class="_fl _r2 avatar-popup-img avatar-medium" />
                                                <div class="_fl short-profile">
                                                    <span class="_fwfl _tb _fwb _fs13 short-info-name">
                                                        {{ get_display_name() }}
                                                    </span>
                                                    <span class="_fwfl  _tb _fwb _fs13">
                                                        {{ user()->email }}
                                                    </span>
                                                </div>
                                            </span>
                                        </a>
                                    </li>
                                    <li role="separator" class="divider"></li>
                                    <li>
                                        @if(user()->has_store)
                                            <a href="{{ route('front_my_store') }}">
                                                <i class="fa fa-eye"></i>
                                                <span>{{ _t('view_store') }}</span>
                                            </a>
                                        @else
                                            <a href="#">
                                                <i class="fa fa-eye"></i>
                                                <span>{{ _t('create_store_link') }}</span>
                                            </a>
                                        @endif
                                    </li>
                                    <li>
                                        <a href="{{ route('front_setting_account') }}">
                                            <i class="fa fa-gear"></i>
                                            <span>{{ _t('setting') }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-life-ring"></i>
                                            <span>{{ _t('help') }}</span>
                                        </a>
                                    </li>
                                    <li role="separator" class="divider"></li>
                                    <li>
                                        <a href="{{ route('front_logout') }}">
                                            <i class="fa fa-sign-out"></i>
                                            <span>{{ _t('signout') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            @else
                            <a href="{{ route('front_register') }}" class="btn _btn _btn-white-link _fr head-signup">{{ _t('signup') }}</a>
                            <a href="{{ route('front_login') }}" class="btn _btn _btn-white-link _fr head-signin">{{ _t('signin') }}</a>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="_fwfl _mt55 biggest-container">
            @yield('content')
        </div>

        <script src="{{ asset('packages/king/frontend/js/jquery_v1.11.1.js') }}"></script>
        <script src="{{ asset('packages/king/frontend/js/jquery-ui-1.11.4.min.js') }}"></script>
        <script src="{{ asset('packages/king/frontend/js/bootstrap.js') }}"></script>
        <script src="{{ asset('packages/king/frontend/js/webtoolkit.aim.js') }}"></script>
        @yield('js')
        <script src="{{ asset('packages/king/frontend/js/setting.js') }}"></script>
        <script src="{{ asset('packages/king/frontend/js/script.js') }}"></script>
    </body>
</html>
