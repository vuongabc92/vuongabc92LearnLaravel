<?php

Route::get('/', ['as' => 'front_home', 'uses' => 'HomeController@index']);

Route::group(['middleware' => 'guest'], function($route){
    $route->match(['get', 'post'], 'login', ['as' => 'front_login', 'uses' => 'AuthController@authenticate']);
    $route->match(['get', 'post'], 'register', ['as' => 'front_register', 'uses' => 'AuthController@register']);
});

Route::group(['middleware' => 'auth'], function(){
    Route::group(['prefix' => 'setting'], function($route){
        $route->get('account', ['as' => 'front_setting_account', 'uses' => 'SettingController@index']);
    });
});