<?php

namespace App\Models;

class District extends Base
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'districts';
    
    public $timestamps = false;
    
    /**
     * Get wards
     * 
     * @return App\Models\Ward
     */
    public function wards()
    {
        return $this->hasMany('App\Models\Ward');
    }
}
