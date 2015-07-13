<?php

namespace App\Models;

class Store extends Base
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stores';
    
    /**
     * Get user
     * 
     * @return App\Models\User
     */
    public function user()
    {
        return $this->hasOne('App\Models\User');
    }
    
    /**
     * Store that owns of city
     * 
     * @return
     */
    public function city() {
        return $this->belongsTo('App\Models\City');
    }
    
    /**
     * Store that owns of district
     * 
     * @return
     */
    public function district() {
        return $this->belongsTo('App\Models\District');
    }
    
    /**
     * Get ward
     * 
     * @return App\Models\Ward
     */
    public function ward() {
        return $this->hasOne('App\Models\Ward');
    }
    
    /**
     * Get products
     * 
     * @return App\Models\Product
     */
    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }
    
    /**
     * Get store validation rules
     * 
     * @return array
     */
    public function getRules() {
        return [
            'name'         => 'required|min:10|max:250',
            'category_id'  => 'required|integer|exists:categories,id',
            'street'       => 'required|max:250',
            'city_id'      => 'required|integer|exists:cities,id',
            'district_id'  => 'required|integer|exists:districts,id',
            'ward_id'      => 'required|integer|exists:wards,id',
            'phone_number' => 'required|max:32',
            'cover'        => 'max:250',
        ];
    }
    
    /**
     * Get store validation messages
     * 
     * @return array
     */
    public function getMessages() {
        return [
            'name.required'         => _t('store_name_req'),
            'name.min'              => _t('store_name_min'),
            'name.max'              => _t('store_name_max'),
            'category_id.required'  => _t('category_id_req'),
            'category_id.integer'   => _t('category_id_int'),
            'category_id.exists'    => _t('category_id_exi'),
            'street.required'       => _t('street_req'),
            'street.max'            => _t('street_max'),
            'city_id.required'      => _t('city_id_req'),
            'city_id.integer'       => _t('city_id_int'),
            'city_id.exists'        => _t('city_id_exi'),
            'district_id.required'  => _t('district_id_req'),
            'district_id.integer'   => _t('district_id_int'),
            'district_id.exists'    => _t('district_id_exi'),
            'ward_id.required'      => _t('ward_id_req'),
            'ward_id.integer'       => _t('ward_id_int'),
            'ward_id.exists'        => _t('ward_id_exi'),
            'phone_number.required' => _t('phone_number_req'),
            'phone_number.max'      => _t('phone_number_max'),
            'cover.max'             => _t('cover_max'),
        ];
    }
}
