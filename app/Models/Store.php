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
}
