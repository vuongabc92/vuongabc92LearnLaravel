<?php

namespace App\Models;

class City extends Base
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cities';

    public $timestamps = false;

    /**
     * Get districts
     *
     * @return App\Models\District
     */
    public function districts()
    {
        return $this->hasMany('App\Models\District');
    }

    /**
     * Get stores
     *
     * @return App\Models\Store
     */
    public function stores()
    {
        return $this->hasMany('App\Models\Store');
    }
}
