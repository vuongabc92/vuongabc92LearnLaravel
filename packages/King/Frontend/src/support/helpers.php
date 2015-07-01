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
        return response()->json($data, $status);
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

if ( ! function_exists('generate_filename')) {
    /**
     * Generate a string that is very very hard to be duplidated.
     * The string consist of current user id (0 for unauthenticated),
     * current microtime, a random string.
     *
     * @param type $prefix
     *
     * @return string
     */
    function generate_filename($prefix = '') {
        $userId    = 0;
        $microtime = microtime(true);
        $randStr   = str_random(10);

        if (auth()->check()) {
            $userId = auth()->user()->id;
        }

        $hashingName = bcrypt($userId . $microtime . $randStr);

        return $prefix . '_' . $hashingName;
    }
}

if ( ! function_exists('remove_rules')) {
    /**
     * Remove one or many rules in a list of rules
     *
     * @param type $rules       List of rules will be removed out
     * @param type $rulesRemove Rule to be found in $rules to remove
     *
     * @return array
     */
    function remove_rules($rules, $rulesRemove) {

        //Remove list rules
        if (is_array($rulesRemove)) {
            if (count($rulesRemove)) {
                foreach ($rulesRemove as $one) {
                    $rules = remove_rules($rules, $one);
                }
            }

            return $rules;
        }

        //Remove a rule string
        if (is_string($rulesRemove)) {
            //If rule string to removing contain dot "." mean
            //remove a rule after dot in field before dot
            if (str_contains($rulesRemove, '.')) {
                $ruleInField = explode('.', $rulesRemove);
                if (isset($rules[$ruleInField[0]])) {
                    $ruleSplit = explode('|', $rules[$ruleInField[0]]);
                    $ruleFlip  = array_flip($ruleSplit);

                    if (isset($ruleFlip[$ruleInField[1]])) {
                        unset($ruleSplit[$ruleFlip[$ruleInField[1]]]);
                    }

                    if (count($ruleSplit)) {
                        $rules[$ruleInField[0]] = implode('|', $ruleSplit);
                    } else {
                        unset($rules[$ruleInField[0]]);
                    }
                }
            } else {
                if (isset($rules[$rulesRemove])) {
                    unset($rules[$rulesRemove]);
                }
            }

            return $rules;
        }

        return $rules;
    }
}

if ( ! function_exists('get_display_name')) {
    function get_display_name() {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->first_name !== '') {
                return $user->first_name . ' ' . $user->last_name;
            }
            
            return $user->user_name;
        }
        
        return '';
    }
}