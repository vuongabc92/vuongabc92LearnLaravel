<?php

Route::get('/', ['as' => 'front_home', 'uses' => 'HomeController@index']);
Route::get('logout', ['as' => 'front_logout', 'uses' => 'AuthController@logout']);

Route::group(['middleware' => 'guest'], function($route){
    $route->match(['get', 'post'], 'login', ['as' => 'front_login', 'uses' => 'AuthController@authenticate']);
    $route->match(['get', 'post'], 'register', ['as' => 'front_register', 'uses' => 'AuthController@register']);
});

Route::group(['middleware' => 'auth'], function(){
    Route::group(['prefix' => 'setting'], function($route){
        $route->get('account', ['as' => 'front_setting_account', 'uses' => 'SettingController@index']);
        $route->post('account/change-basic', ['as' => 'front_setting_acc_basic', 'uses' => 'SettingController@ajaxSaveBasicInfo']);
        $route->post('account/change-pass', ['as' => 'front_setting_change_pass', 'uses' => 'SettingController@ajaxChangePassword']);
        $route->post('account/change-avatar', ['as' => 'front_setting_change_avatar', 'uses' => 'SettingController@ajaxChangeAvatar']);

        $route->get('store', ['as' => 'front_setting_store', 'uses' => 'SettingController@store']);
        $route->post('store/change-info', ['as' => 'front_setting_store_change', 'uses' => 'SettingController@ajaxSaveStoreInfo']);
        $route->get('store/change-district/{id}', ['as' => 'front_setting_change_district', 'uses' => 'SettingController@ajaxGetDistrictByCityId']);
    });
});