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

if ( ! function_exists('ajax_response')) {
    /**
     * Return a new JSON response from the application.
     *
     * @param  string|array  $data
     * @param  int           $status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    function ajax_response($data = [], $status = 200) {
        echo response()->json($data, $status);
        exit;
    }
}

if ( ! function_exists('str_equal')) {
    /**
     * Compares two strings using a constant-time algorithm.
     *
     * Note: This method will leak length information.
     *
     * Note: Adapted from Symfony\Component\Security\Core\Util\StringUtils.
     *
     * @param  string  $knownString
     * @param  string  $userInput
     * 
     * @return bool
     */
    function str_equal($knownString, $userInput) {
        return \Illuminate\Support\Str::equals($knownString, $userInput);
    }
}