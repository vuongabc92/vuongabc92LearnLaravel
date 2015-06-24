<?php

Route::group(['middleware' => 'guest'], function($route){
    $route->match(['get', 'post'], 'login', ['as' => 'front_login', 'uses' => 'AuthController@authenticate']);
    $route->match(['get', 'post'], 'register', ['as' => 'front_register', 'uses' => 'AuthController@register']);
});

Route::get('/', ['as' => 'front_home', 'uses' => 'HomeController@index']);