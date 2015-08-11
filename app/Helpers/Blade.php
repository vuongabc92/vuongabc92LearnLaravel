<?php

namespace App\Helpers;

use Blade as _Blade;

class Blade {

    public function __construct() {

        _Blade::extend(function($value) {
            return preg_replace('/\@set(.+)/', '<?php ${1}; ?>', $value);
        });
    }
}