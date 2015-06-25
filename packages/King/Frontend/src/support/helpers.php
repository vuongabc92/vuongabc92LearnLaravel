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

if ( ! function_exists('get_avatar')) {
    
    /**
     * Get avatar path
     * if the avatar does not exist, default avatar will be retrieved
     * 
     * @return string Path to avatar
     */
    function get_avatar() {
        $currentAvatar = auth()->user()->avatar;
        if ($currentAvatar !== null) {
            $avatarPath = config('front.avatar_path') . '/' . $currentAvatar;
            if ( ! is_dir($avatarPath) && file_exists($avatarPath)) {
                return $avatarPath;
            }
        }
        
        return asset(config('front.default_avatar_path'));
    }
}