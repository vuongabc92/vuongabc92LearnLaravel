<?php

if ( ! function_exists('_t')) {
    /**
     * Return the sring was translated
     * 
     * @param string $string
     * 
     * @return string
     */
    function _t($string) {
        return trans("frontend::frontend.{$string}");
    }
}
