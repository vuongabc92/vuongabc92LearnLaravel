<?php
//Home
Route::get('/', ['as' => 'front_home', 'uses' => 'HomeController@index']);

//Location
Route::post('/search-location', ['as' => 'front_search_location', 'uses' => 'HomeController@ajaxSearchLocation']);
Route::get('/select-location/{id}', ['as' => 'front_select_location', 'uses' => 'HomeController@ajaxSelectLocation']);

//Logout
Route::get('logout', ['as' => 'front_logout', 'uses' => 'AuthController@logout']);

Route::group(['middleware' => 'guest'], function($route){
    $route->match(['get', 'post'], 'login', ['as' => 'front_login', 'uses' => 'AuthController@authenticate']);
    $route->match(['get', 'post'], 'register', ['as' => 'front_register', 'uses' => 'AuthController@register']);
});

Route::group(['middleware' => 'auth'], function(){
    Route::group(['prefix' => 'setting'], function($route){
        //Setting account
        $route->get('account', ['as' => 'front_setting_account', 'uses' => 'SettingController@index']);
        $route->post('account/change-basic', ['as' => 'front_setting_acc_basic', 'uses' => 'SettingController@ajaxSaveBasicInfo']);
        $route->post('account/change-pass', ['as' => 'front_setting_change_pass', 'uses' => 'SettingController@ajaxChangePassword']);
        $route->post('account/change-avatar', ['as' => 'front_setting_change_avatar', 'uses' => 'SettingController@ajaxChangeAvatar']);

        //Setting store
        $route->get('store', ['as' => 'front_setting_store', 'uses' => 'SettingController@store']);
        $route->post('store/change-info', ['as' => 'front_setting_store_change', 'uses' => 'SettingController@ajaxSaveStoreInfo']);
        $route->get('store/get-district/{id}', ['as' => 'front_setting_get_district', 'uses' => 'SettingController@ajaxGetDistrictByCityId']);
        $route->get('store/get-ward/{id}', ['as' => 'front_setting_get_ward', 'uses' => 'SettingController@ajaxGetWardByCityId']);
        $route->post('store/change-cover', ['as' => 'front_setting_change_cover', 'uses' => 'SettingController@ajaxChangeCover']);

        //Store
        $route->get('my-store', ['as' => 'front_my_store', 'uses' => 'StoreController@index']);
    });
});