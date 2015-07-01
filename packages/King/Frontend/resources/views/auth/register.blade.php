@extends('frontend::layouts._auth')

@section('title')
Register
@stop

@section('head-link')
<a class="btn _btn _btn-white-link _fr auth-head-btn" href="{{ url(route('front_login')) }}">{{ _t('signin') }}</a>
@stop

@section('body')
    {!! Form::open(['route' => 'front_register', 'method' => 'POST', 'class' => '_ma auth-form', 'data-required' => 'username|email|password']) !!}
        <h1 class="_fwfl _m0 _p0 auth-form-title">{{ _t('signup_title') }}</h1>
        <div class="_fwfl auth-field-group first-field-group">
            <label class="_fwfl _fs14 _fwn _tg5" for="username">
                @if ($errors->auth->first('user_name') !== '')
                <span class="_tr5">{{ $errors->auth->first('user_name') }}</span>
                @else
                {{ _t('username') }}
                @endif
            </label>
            <div class="_fwfl">
                {!! Form::text('user_name', '', ['class' => '_fwfl  _ff0 _r2 auth-field', 'id' => 'username', 'maxlength' => '32']) !!}
            </div>
        </div>
        <div class="_fwfl auth-field-group">
            <label class="_fwfl _fs14 _fwn _tg5" for="email">
                @if ($errors->auth->first('email') !== '')
                <span class="_tr5">{{ $errors->auth->first('email') }}</span>
                @else
                {{ _t('email') }}
                @endif
            </label>
            <div class="_fwfl">
                {!! Form::text('email', '', ['class' => '_fwfl  _ff0 _r2 auth-field', 'id' => 'email', 'maxlength' => '128']) !!}
            </div>
        </div>
        <div class="_fwfl auth-field-group">
            <label class="_fwfl _fs14 _fwn _tg5" for="password">
                @if ($errors->auth->first('password') !== '')
                <span class="_tr5">{{ $errors->auth->first('password') }}</span>
                @else
                {{ _t('password') }}
                @endif
            </label>
            <div class="_fwfl">
                {!! Form::password('password', ['class' => '_ff0 _r2 _fwfl auth-field', 'id' => 'password', 'maxlength' => '60']) !!}
            </div>
        </div>
        <div class="_fwfl _tg5 auth-field-group">
            {{ _t('agreed') }}
        </div>
        <div class="_fwfl auth-field-group">
            <button class="_fwfl btn _btn _btn-blue"><i class="fa fa-arrow-right"></i></button>
        </div>
    {!! Form::close() !!}
@stop