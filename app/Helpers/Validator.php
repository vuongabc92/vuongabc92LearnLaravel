<?php

namespace App\Helpers;

use Validator as _Validator;

class Validator {

    public function __construct() {

        _Validator::extend('product_image', function($attribute, $value, $parameters) {

            

        });
    }
}