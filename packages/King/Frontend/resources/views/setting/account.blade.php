@extends('frontend::layouts._frontend')

@section('title')
Setting > Account
@stop

@section('content')
<div class="_mw970 _ma">
    <div class="_fwfl setting-container">
        <div class="_fl _bgw _r3 setting-left-col">
            <div class="_fwfl setting-header">
                <h1 class="_fwfl _p0 _m0 _fs20 _tg5 _fs20">{{ _t('acc') }}</h1>
            </div>
            <div class="_fwfl">
                <div class="_fwfl setting-group">
                    <div class="_fl setting-field-left">
                        <b class="_fwfl _fs13 _tg5">{{ _('bussiness') }}</b>
                    </div>
                    <div class="_fr setting-field-right">
                        <span class="_fwfl _tg7">
                            {{ _t('buss_note') }}
                            <!--<b class="_fs13 _tup _tb">Shop Quần Áo Liên Liên</b>-->
                        </span>

                        <div class="_fwfl _mt10">
                            <a class="btn _btn _btn-blue1" href="#">+ {{ _t('create_store') }}</a>
                        </div>
                    </div>
                </div>
                <div class="_fwfl setting-group">
                    <div class="_fl setting-field-left">
                        <b class="_fwfl _fs13 _tg5">{{ _t('avatar') }}</b>
                    </div>
                    <div class="_fr setting-field-right">
                        <span class="_fwfl">
                            <img class="_fl img-circle setting-avatar-img" src="{{ get_avatar() }}" />
                            <span class="_fl _m20 _fs12 _tga">
                                <p class="_m0">+ {{ _t('avatar_note1') }}</p>
                                <p class="_m0">+ {{ _t('avatar_note2') }}</p>
                            </span>
                        </span>
                        <div class="_fwfl _mt15">
                            <span class="btn _btn _btn-white" data-event-trigger="#avatar-file" data-event="click|click">{{ _t('avatar_browser') }}</span>
                        </div>
                        <div class="_fwfl _dn">
                            {!! Form::open(['route' => 'front_setting_change_avatar', 'files' => true, 'method' => 'POST', 'id' => 'upload-avatar-form', 'data-upload-avatar']) !!}
                            {!! Form::file('avatar', ['class' => 'field-file-hidden', 'id' => 'avatar-file', 'data-event-trigger' => '#upload-avatar-form', 'data-event' => 'change|submit']) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                <div class="_fwfl setting-group">
                    <div class="_fl setting-field-left">
                        <b class="_fwfl _fs13 _tg5">{{ _t('password') }}</b>
                    </div>
                    <div class="_fr setting-field-right">
                        <div class="_fwfl change-pass-btn">
                            <span class="_fwfl _fs13 _tg9">{{ _t('pass_note') }}</span>
                            <span class="btn _btn _btn-red _mt10 show-pass-form">{{ _t('change_pass') }}</span>
                        </div>

                        {!! Form::open(['route' => 'front_setting_change_pass', 'method' => 'POST', 'class' => '_fl _dn setting-form setting-form-pass', 'data-ajax-form' => 'password|new_password']) !!}
                        <div class="_fwfl setting-form-group">
                            <label class="_fwfl setting-form-label" for="password" data-title="{{ _t('cur_pass') }}">{{ _t('cur_pass') }}</label>
                            {!! Form::password('password', ['class' => '_fwfl setting-form-field', 'id' => 'password', 'maxlength' => '60']) !!}
                        </div>
                        <div class="_fwfl setting-form-group">
                            <label class="_fwfl setting-form-label" for="new-password" data-title="{{ _t('new_pass') }}">{{ _t('new_pass') }}</label>
                            {!! Form::password('new_password', ['class' => '_fwfl setting-form-field', 'id' => 'new-password', 'maxlength' => '60']) !!}
                        </div>
                        <div class="_fwfl setting-form-group">
                            <button type="submit" class="_fl _mr10 btn _btn _btn-blue1">
                                <img class="loading-in-btn" src="{{ asset('packages/king/frontend/images/loading-white-blue1.gif') }}" />
                                <b class="btn-text">{{ _t('save') }}</b>
                                <i class="fa fa-check _dn"></i>
                            </button>
                            <button type="reset" class="_fl btn _btn _btn-gray close-form-pass">{{ _t('cancel') }}</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                <div class="_fwfl setting-group">
                    <div class="_fl setting-field-left">
                        <b class="_fwfl _fs13 _tg5">{{ _t('basic_info') }}</b>
                    </div>
                    <div class="_fr setting-field-right">
                        {!! Form::model($user, ['route' => 'front_setting_acc_basic', 'method' => 'POST', 'class' => '_fl setting-form', 'data-ajax-form' => 'user_name|email|first_name|last_name|password']) !!}
                        <div class="_fwfl setting-form-group">
                            <label class="_fwfl setting-form-label" for="username" data-title="{{ _t('username') }}">{{ _t('username') }}</label>
                            {!! Form::text('user_name', null, ['class' => '_fwfl setting-form-field', 'id' => 'username', 'maxlength' => '32']) !!}
                        </div>
                        <div class="_fwfl setting-form-group">
                            <label class="_fwfl setting-form-label" for="email" data-title="{{ _t('email') }}">{{ _t('email') }}</label>
                            {!! Form::text('email', null, ['class' => '_fwfl setting-form-field', 'id' => 'email', 'maxlength' => '128']) !!}
                        </div>
                        <div class="_fwfl setting-form-group">
                            <label class="_fwfl setting-form-label" for="firstname" data-title="{{ _t('fname') }}">{{ _t('fname') }}</label>
                            {!! Form::text('first_name', null, ['class' => '_fwfl setting-form-field', 'id' => 'firstname', 'maxlength' => '16']) !!}
                        </div>
                        <div class="_fwfl setting-form-group">
                            <label class="_fwfl setting-form-label" for="lastname" data-title="{{ _t('lname') }}">{{ _t('lname') }}</label>
                            {!! Form::text('last_name', null, ['class' => '_fwfl setting-form-field', 'id' => 'lastname', 'maxlength' => '32']) !!}
                        </div>
                        <div class="_fwfl setting-form-group">
                            <label class="_fwfl setting-form-label" for="password" data-title="{{ _t('pass_confirm') }}">{{ _t('pass_confirm') }}</label>
                            {!! Form::password('password', ['class' => '_fwfl setting-form-field', 'id' => 'password', 'maxlength' => '60']) !!}
                            <span class="_fwfl _fs13 _tg5 _mt5">(*) Password for change user name or email</span>
                        </div>
                        <div class="_fwfl setting-form-group">
                            <button type="submit" class="_fl _mr10 btn _btn _btn-blue1">
                                <img class="loading-in-btn" src="{{ asset('packages/king/frontend/images/loading-white-blue1.gif') }}" />
                                <b class="btn-text">{{ _t('save') }}</b>
                                <i class="fa fa-check _dn"></i>
                            </button>
                            <button type="reset" class="_fl btn _btn _btn-gray">{{ _t('cancel') }}</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    /**
     * Display form change password or hide it.
     */
    $('.show-pass-form').on('click', function(){
        $('.setting-form-pass').show();
        $('.change-pass-btn').hide();
    });

    $('.close-form-pass').on('click', function(){
        $('.setting-form-pass').hide();
        $('.change-pass-btn').show();
    });
    /** END */
</script>
@stop

<!--                                <script type="text/javascript">
    function startCallback() {
        //$('.setting-avatar-response-error').hide();
        //$('.setting-acc-avatar-loading').show();

        browserAvatarLoading(1)

        return true;
    }

    function completeCallback(response) {
        browserAvatarLoading(0);
        response = JSON.parse(response);
        //$('.setting-acc-avatar-loading').hide();
        if (response.status === 'OK') {
            //$('.setting-avatar-response-error').hide();
            //$('.setting-avatar-response-ok').show();

            $('.setting-avatar-img').attr('src', response.data);
            $('.header-avatar-img').attr('src', response.data);
            $('.header-profile-popup-avatar').attr('src', response.data);
            browserAvatarOk(1);
            setTimeout(function() {
                $('.setting-avatar-response-ok').hide();
            }, 2000);
        } else {
            var error = response.data;
            $('.setting-avatar-response-error').show();
            $('.upload-avatar-msg').html(error.setting_user_avatar);
        }
    }

    function browserAvatarLoading(status) {
        if (status === 1) {
            $('.browse-avatar-text').hide();
            $('.browse-avatar-ok').hide();
            $('.browse-avatar-gray-loading').show();
            $('.setting-submit-text-loading').show();
            $('.setting-acc-browse-img-btn').addClass('browse-avatar-loading-btn');
        } else {
            $('.browse-avatar-text').show();
            $('.browse-avatar-gray-loading').hide();
            $('.setting-submit-text-loading').hide();
            $('.setting-acc-browse-img-btn').removeClass('browse-avatar-loading-btn');
        }
    }

    function browserAvatarOk(status) {
        if (status === 1) {
            $('.browse-avatar-ok').show();
            setTimeout(function() {
                $('.browse-avatar-ok').hide();
            }, 2000);
        }
    }
</script>-->