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
                            <a class="btn _btn _btn-blue1 _r2" href="#">+ {{ _t('create_store') }}</a>
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
                            <button class="btn _btn _btn-white">{{ _t('avatar_browser') }}</button>
                        </div>
                    </div>
                </div>
                <div class="_fwfl setting-group">
                    <div class="_fl setting-field-left">
                        <b class="_fwfl _fs13 _tg5">{{ _t('password') }}</b>
                    </div>
                    <div class="_fr setting-field-right">
                        <span class="_fwfl _fs13 _tg9">{{ _t('pass_note') }}</span>
                        <span class="btn _btn _btn-red _mt10">{{ _t('change_pass') }}</span>
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
                            {!! Form::text('user_name', null, ['class' => '_fwfl setting-form-field', 'id' => 'username', 'maxlength' => '32', 'data-original-val' => $user->user_name]) !!}
                        </div>
                        <div class="_fwfl setting-form-group">
                            <label class="_fwfl setting-form-label" for="email" data-title="{{ _t('email') }}">{{ _t('email') }}</label>
                            {!! Form::text('email', null, ['class' => '_fwfl setting-form-field', 'id' => 'email', 'maxlength' => '128', 'data-original-val' => $user->email]) !!}
                        </div>
                        <div class="_fwfl setting-form-group">
                            <label class="_fwfl setting-form-label" for="firstname" data-title="{{ _t('fname') }}">{{ _t('fname') }}</label>
                            {!! Form::text('first_name', null, ['class' => '_fwfl setting-form-field', 'id' => 'firstname', 'maxlength' => '16']) !!}
                        </div>
                        <div class="_fwfl setting-form-group">
                            <label class="_fwfl setting-form-label" for="lastname" data-title="{{ _t('lname') }}">{{ _t('lname') }}</label>
                            {!! Form::text('last_name', null, ['class' => '_fwfl setting-form-field', 'id' => 'lastname', 'maxlength' => '32']) !!}
                        </div>
                        <div class="_fwfl setting-form-group _dn pass-group">
                            <label class="_fwfl setting-form-label" for="password" data-title="{{ _t('password') }}">{{ _t('password') }}</label>
                            {!! Form::password('password', ['class' => '_fwfl setting-form-field', 'id' => 'password', 'maxlength' => '60']) !!}
                        </div>
                        <div class="_fwfl setting-form-group">
                            <button type="submit" class="_fl _mr10 btn _btn _btn-blue1">{{ _t('save') }}</button>
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
    $('#username, #email').on('keyup', function(){
        var t = $(this),
            v = t.val().trim();

        if (v !== t.attr('data-original-val')) {
            $('.pass-group').show();
        } else {
            $('.pass-group').hide();
        }
    });
</script>
@stop