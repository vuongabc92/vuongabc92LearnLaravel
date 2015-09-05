<?php

namespace App\Models;
use Illuminate\Container\Container;

class Pin extends Base
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pin';

    public $timestamps = false;
    
    public function isPinned() {

        $userId   = Container::getInstance()->make('Illuminate\Contracts\Auth\Guard')->user()->id;
        $pinUsers = json_decode($this->user_id, true);

        return isset($pinUsers[$userId]);
    }
}
