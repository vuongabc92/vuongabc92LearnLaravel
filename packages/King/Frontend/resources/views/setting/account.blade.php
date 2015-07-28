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
                        <b class="_fwfl _fs13 _tg5">{{ _t('bussiness') }}</b>
                    </div>
                    <div class="_fr setting-field-right">
                        <span class="_fwfl _tg7">
                            @if(user()->has_store)
                                <b class="_fs13 _tup _tb">{{ store()->name }}</b>
                            @else
                                {{ _t('buss_note') }}
                            @endif
                        </span>

                        <div class="_fwfl _mt10">
                            <a class="btn _btn _btn-blue1" href="{{ route('front_setting_store') }}">
                                @if(user()->has_store)
                                    <i class="fa fa-gear"></i> {{ _t('setting_store') }}
                                @else
                                    + {{ _t('create_store') }}
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
                <div class="_fwfl setting-group">
                    <div class="_fl setting-field-left">
                        <b class="_fwfl _fs13 _tg5">{{ _t('avatar') }}</b>
                    </div>
                    <div class="_fr setting-field-right">
                        <span class="_fwfl">
                            <img class="_fl img-circle setting-avatar-img avatar-big" src="{{ get_avatar('big') }}" />
                            <span class="_fl _ml20 _fs12 _tga avatar-note-group">
                                <p class="_m0">+ {{ _t('avatar_note1') }}</p>
                                <p class="_m0">+ {{ _t('avatar_note2') }}</p>
                                <p class="_m0">+ {{ _t('avatar_note3') }}</p>
                            </span>
                        </span>
                        <div class="_fwfl _mt15">
                            <span class="btn _btn _btn-white choose-avatar-btn" data-event-trigger="#avatar-file" data-event="click|click">
                                <img class="loading-in-btn" src="{{ asset('packages/king/frontend/images/loading-gray-white-24x24.gif') }}" />
                                <b class="btn-text">{{ _t('avatar_browser') }}</b>
                                <i class="fa fa-check _dn"></i>
                            </span>
                            <span class="_fwfl _tr7 _fs12 _mt10 _dn upload-avatar-messages"></span>
                        </div>
                        <div class="_fwfl _dn">
                            {!! Form::open(['route' => 'front_setting_change_avatar', 'files' => true, 'method' => 'POST', 'id' => 'upload-avatar-form', 'data-upload-avatar']) !!}
                            {!! Form::file('__file', ['class' => 'field-file-hidden', 'id' => 'avatar-file', 'accept' => 'image/*', 'data-event-trigger' => '#upload-avatar-form', 'data-event' => 'change|submit']) !!}
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

                        {!! Form::open(['route' => 'front_setting_change_pass', 'method' => 'POST', 'class' => '_fl _dn setting-form setting-form-pass', 'id' => 'change-pass-form', 'data-ajax-form' => 'password|new_password']) !!}
                        <div class="_fwfl setting-form-group">
                            <label class="_fwfl setting-form-label" for="password" data-title="{{ _t('cur_pass') }}">{{ _t('cur_pass') }}</label>
                            {!! Form::password('password', ['class' => 'setting-form-field', 'id' => 'password', 'maxlength' => '60']) !!}
                        </div>
                        <div class="_fwfl setting-form-group">
                            <label class="_fwfl setting-form-label" for="new-password" data-title="{{ _t('new_pass') }}">{{ _t('new_pass') }}</label>
                            {!! Form::password('new_password', ['class' => 'setting-form-field', 'id' => 'new-password', 'maxlength' => '60']) !!}
                        </div>
                        <div class="_fwfl setting-form-group">
                            <button type="submit" class="_fl _mr10 btn _btn _btn-blue1 _save-btn">
                                <img class="loading-in-btn" src="{{ asset('packages/king/frontend/images/loading-white-blue-24x24.gif') }}" />
                                <b class="btn-text">{{ _t('save') }}</b>
                                <i class="fa fa-check _dn"></i>
                            </button>
                            <button type="reset" class="_fl btn _btn _btn-gray close-form-pass" data-reset-form="#change-pass-form">{{ _t('cancel') }}</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                <div class="_fwfl setting-group">
                    <div class="_fl setting-field-left">
                        <b class="_fwfl _fs13 _tg5">{{ _t('basic_info') }}</b>
                    </div>
                    <div class="_fr setting-field-right">
                        {!! Form::model($user, ['route' => 'front_setting_acc_basic', 'method' => 'POST', 'class' => '_fl setting-form', 'id' => 'account-basic-form', 'data-ajax-form' => 'user_name|email|first_name|last_name|password']) !!}
                        <div class="_fwfl setting-form-group">
                            <label class="_fwfl setting-form-label" for="username" data-title="{{ _t('username') }}">{{ _t('username') }}</label>
                            {!! Form::text('user_name', null, ['class' => 'setting-form-field', 'id' => 'username', 'maxlength' => '32']) !!}
                        </div>
                        <div class="_fwfl setting-form-group">
                            <label class="_fwfl setting-form-label" for="email" data-title="{{ _t('email') }}">{{ _t('email') }}</label>
                            {!! Form::text('email', null, ['class' => 'setting-form-field', 'id' => 'email', 'maxlength' => '128']) !!}
                        </div>
                        <div class="_fwfl setting-form-group">
                            <label class="_fwfl setting-form-label" for="firstname" data-title="{{ _t('fname') }}">{{ _t('fname') }}</label>
                            {!! Form::text('first_name', null, ['class' => 'setting-form-field', 'id' => 'firstname', 'maxlength' => '16']) !!}
                        </div>
                        <div class="_fwfl setting-form-group">
                            <label class="_fwfl setting-form-label" for="lastname" data-title="{{ _t('lname') }}">{{ _t('lname') }}</label>
                            {!! Form::text('last_name', null, ['class' => 'setting-form-field', 'id' => 'lastname', 'maxlength' => '32']) !!}
                        </div>
                        <div class="_fwfl setting-form-group">
                            <label class="_fwfl setting-form-label" for="password-confirm-basic" data-title="{{ _t('pass_confirm') }}">{{ _t('pass_confirm') }}</label>
                            {!! Form::password('password', ['class' => 'setting-form-field', 'id' => 'password-confirm-basic', 'maxlength' => '60']) !!}
                            <span class="_fwfl _fs13 _tg5 _mt5">(*) {{ _t('basic_info_note1') }}</span>
                        </div>
                        <div class="_fwfl setting-form-group">
                            <button type="submit" class="_fl _mr10 btn _btn _btn-blue1 _save-btn">
                                <img class="loading-in-btn" src="{{ asset('packages/king/frontend/images/loading-white-blue-24x24.gif') }}" />
                                <b class="btn-text">{{ _t('save') }}</b>
                                <i class="fa fa-check _dn"></i>
                            </button>
                            <button type="reset" class="_fl btn _btn _btn-gray" data-reset-form="#account-basic-form">{{ _t('cancel') }}</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                <div class="_fwfl setting-group last-setting-group">
                    <button class="_fr btn _btn _btn-red">{{ _t('del_acc') }}</button>
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