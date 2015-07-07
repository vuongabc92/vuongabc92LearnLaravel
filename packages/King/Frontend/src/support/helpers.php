<?php

if ( ! function_exists('_t')) {

    /**
     * Translate the given message.
     *
     * @param  string  $id
     * @param  array   $parameters
     * @param  string  $domain
     * @param  string  $locale
     *
     * @return string
     */
    function _t($id = null, $parameters = [], $domain = 'messages', $locale = null) {
        return trans("frontend::frontend.{$id}", $parameters = [], $domain = 'messages', $locale = null);
    }

}

if ( ! function_exists('_const')) {
    /**
     * Get / set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string  $key
     * @param  mixed         $default
     *
     * @return mixed
     */
    function _const($key = null, $default = null) {
        return config("constant.{$key}", $default);
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
            $avatarPath = config('front.avatar_path') . $currentAvatar;
            if (check_file($avatarPath)) {
                return asset($avatarPath);
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
     * @param  array         $headers
     * @param  int           $options
     *
     * @return \Illuminate\Http\JsonResponse
     */
    function ajax_response($data = [], $status = 200, array $headers = [], $options = 0) {
        return response()->json($data, $status, $headers, $options);
    }

}

if ( ! function_exists('ajax_upload_response')) {

    /**
     * Return a new JSON response from the application.
     *
     * @param  string|array  $data
     * @param  int           $status
     * @param  int           $options
     *
     * @return \Illuminate\Http\JsonResponse
     */
    function ajax_upload_response($data = [], $status = 200, $options = 0) {
        return response()->json($data, $status, ['Content-Type' => 'text/html'], $options);
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
        if (is_array($rulesRemove) && count($rulesRemove)) {
            foreach ($rulesRemove as $one) {
                $rules = remove_rules($rules, $one);
            }

            return $rules;
        }

        /**
         * Remove rule string
         * 1. If rule contains dot "." then remove rule after dot for rule name
         *    before the dot.
         * 2. If rule doesn't contain dot then remove the rule name present
         *
         */
        if (is_string($rulesRemove)) {

            if (str_contains($rulesRemove, '.')) {
                $ruleInField = explode('.', $rulesRemove);
                if (isset($rules[$ruleInField[0]])) {
                    $ruleSplit = explode('|', $rules[$ruleInField[0]]);
                    $ruleFlip  = array_flip($ruleSplit);

                    if (isset($ruleFlip[$ruleInField[1]])) {
                        unset($ruleSplit[$ruleFlip[$ruleInField[1]]]);
                    }

                    //Remove the rule name if it contains no rule
                    if (count($ruleSplit)) {
                        $rules[$ruleInField[0]] = implode('|', $ruleSplit);
                    } else {
                        unset($rules[$ruleInField[0]]);
                    }
                }

            } elseif (isset($rules[$rulesRemove])) {
                unset($rules[$rulesRemove]);
            }

            return $rules;
        }

        return $rules;
    }

}

if (!function_exists('get_display_name')) {

    /**
     * Get user display name
     * get first name and last name or get user name if
     * first name last name does not exist.
     *
     * @return string
     */
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

if ( ! function_exists('generate_filename')) {

    /**
     * Generate the file name base on current user id, time
     * to get a unique file in present folder
     *
     * @param string $directory Path to the upload directory
     * @param string $extension File extension
     * @param string $prefix    File prefix
     * @param string $suffix    File suffix
     *
     * @return string   File name
     */
    function generate_filename($directory, $extension, $options = []) {

        $userId    = 0;
        $microtime = microtime(true);
        $randStr   = str_random(10);

        if (auth()->check()) {
            $userId = auth()->user()->id;
        }

        $prefix       = isset($options['prefix']) ? $options['prefix'] : '';
        $suffix       = isset($options['suffix']) ? $options['suffix'] : '';
        $nameEncoding = md5($userId . $microtime . $randStr);
        $fileName     = $prefix . $nameEncoding . $suffix . '.' . $extension;

        while (check_file($directory . $fileName)) {
            $fileName = generate_filename($directory, $extension . $prefix);
        }

        return $fileName;
    }

}

if ( ! function_exists('check_file')) {

    /**
     * Check does the present file exist
     *
     * @param string $file Path to file
     *
     * @return boolean
     */
    function check_file($file) {

        if ( ! is_dir($file) && file_exists($file)) {
            return true;
        }

        return false;
    }

}

if ( ! function_exists('upload')) {
    /**
     *
     * @param Illuminate\Http\Request $request
     * @param string                  $directory
     * @param string                  $oldFile
     * @param array                   $options
     *
     * The param options contains data below:
     * <pre>
     * array(
     *  'prefix',
     *  'suffix',
     *  'resize' => array(
     *      'width' => xx
     *      'height' => xx
     *  )
     * )
     * </pre>
     *
     * @return string
     *
     * @throws \Exception
     */
    function upload($request, $directory, $oldFile, $options = []) {

        /** Remove current file if exist. */
        if ($oldFile !== null) {
            delete_file($directory . $oldFile);
        }

        $file              = $request->file('__file');
        $fileExt           = $file->getClientOriginalExtension();
        $newFileNamePrefix = isset($options['prefix']) ? $options['prefix'] : '';
        $newFileNameSuffix = isset($options['suffix']) ? $options['suffix'] : '';
        $newFiles          = [];
        $newFileName       = generate_filename($directory, $fileExt, [
            'prefix' => $newFileNamePrefix,
            'suffix' => $newFileNameSuffix
        ]);

        try {
            $file->move($directory, $newFileName);

            if (isset($options['resize']) && count($options['resize'])) {
                foreach ($options['resize'] as $k => $v) {
                    $suffix = ($newFileNameSuffix !== '') ? $newFileNameSuffix : '_' . $k;
                    $resizeFileName = generate_filename($directory, $fileExt, [
                        'prefix' => $newFileNamePrefix,
                        'suffix' => $suffix
                    ]);
                    $resizeWidth  = $v['width'];
                    $resizeHeight = $v['height'];

                    resize_image($directory . $resizeFileName, $resizeWidth, $resizeHeight);
                }
            }
        } catch (Exception $ex) {
            throw new \Exception(_t('opp'));
        }

        return count($newFiles) ? $newFiles : $newFileName;
    }

}

if ( ! function_exists('delete_file')) {

    /**
     * Delete file
     *
     * @param string $path
     *
     * @return boolean
     *
     * @throws \Exception
     */
    function delete_file($path) {

        if (check_file($path)) {
            try {
                \Illuminate\Support\Facades\File::delete($path);
            } catch (Exception $ex) {
                throw new \Exception(_t('opp'));
            }
        }

        return true;
    }

}

if ( ! function_exists('resize_image')) {

    /**
     * Resize image
     *
     * @param string $imagePath
     * @param int    $width
     * @param int    $height
     * @param string $newName
     */
    function resize_image($imagePath, $width, $height, $newName = '') {

        //Only resize when the width and height is specified.
        if ($width && $height) {
            $image = \Intervention\Image\Facades\Image::make($imagePath)->orientate();
            $image->fit($width, $height, function ($constraint) {
                $constraint->upsize();
            });

            if ($newName !== '') {
                $image->save($newName);
            }

            $image->save();
        }
    }

}

