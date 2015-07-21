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

if ( ! function_exists('user')) {
    /**
     * Current authenticated user
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    function user() {
        if (auth()->check()) {
            return auth()->user();
        }

        return null;
    }
}

if ( ! function_exists('store')) {
    /**
     * Current authenticated user's store if exist
     *
     * @return \App\Models\Store|null
     */
    function store() {
        if (user()->has_store) {
            return user()->store;
        }

        return null;
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
     * @param int $size Get avatar with exist size
     *
     * @return string Path to avatar
     */
    function get_avatar($size = false) {

        $avatars = [];
        foreach (['big', 'medium', 'small'] as $one) {
            $avatars[$one] = avatar_default($one);
            if (avatar_size($one) !== false) {
                $avatars[$one] = avatar_size($one);
            }
        }

        return ($size && isset($avatars[$size])) ? $avatars[$size] : false;
    }

}

if ( ! function_exists('avatar_size')) {
    /**
     * Get avatar by size
     *
     * @param int $size
     *
     * @return boolean|string
     */
    function avatar_size($size = false){
        if ($size && auth()->check()) {
            $avatar     = 'avatar_' . $size;
            $avatarPath = config('front.avatar_path') . user()->$avatar;
            if (check_file($avatarPath)) {
                return asset($avatarPath);
            }

            return false;
        }

        return false;
    }
}

if ( ! function_exists('avatar_default')) {
    /**
     * Get avatar default
     *
     * @param string $size
     *
     * @return string|array
     */
    function avatar_default($size = false) {
        $avatars = [
            'big'    => asset(config('front.default_avatar_path')),
            'medium' => asset(config('front.default_avatar_path')),
            'small'  => asset(config('front.default_avatar_path')),
        ];

        return ($size && isset($avatars[$size])) ? $avatars[$size] : $avatars;
    }
}

if ( ! function_exists('get_cover')) {

    /**
     * Get cover path
     * if the cover does not exist, default cover will be retrieved
     *
     * @param int $size Get cover with exist size
     *
     * @return string Path to cover
     */
    function get_cover($size = false) {

        $covers = [];
        foreach (['big', 'medium', 'small'] as $one) {
            $covers[$one] = cover_default($one);
            if (cover_size($one) !== false) {
                $covers[$one] = cover_size($one);
            }
        }

        return ($size && isset($covers[$size])) ? $covers[$size] : false;
    }

}

if ( ! function_exists('cover_size')) {
    /**
     * Get cover by size
     *
     * @param int $size
     *
     * @return boolean|string
     */
    function cover_size($size = false){
        if ($size && user()->has_store) {
            $cover      = 'cover_' . $size;
            $coverPath = config('front.cover_path') . store()->$cover;
            if (check_file($coverPath)) {
                return asset($coverPath);
            }

            return false;
        }

        return false;
    }
}

if ( ! function_exists('cover_default')) {
    /**
     * Get cover default
     *
     * @param string $size
     *
     * @return string|array
     */
    function cover_default($size = false) {
        $covers = [
            'big'    => asset(config('front.default_cover_path')),
            'medium' => asset(config('front.default_cover_path')),
            'small'  => asset(config('front.default_cover_path')),
        ];

        return ($size && isset($covers[$size])) ? $covers[$size] : $covers;
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
            $user = user();
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
     * @param array  $options   Prefix, suffix,...
     *
     * @return string
     */
    function generate_filename($directory, $extension, $options = []) {

        $userId    = 0;
        $microtime = microtime(true);
        $randStr   = str_random(10);

        if (auth()->check()) {
            $userId = user()->id;
        }

        $prefix       = isset($options['prefix']) ? $options['prefix']  : '';
        $suffix       = isset($options['suffix']) ? $options['suffix']  : '';
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
     * @param array                   $oldFiles
     * @param array                   $options
     *
     * The param options contains data below:
     * <pre>
     * array(
     *  'prefix',
     *  'suffix',
     *  'resize' => [
     *    'xx' => [
     *      'width' => xx,
     *      'height' => xx
     *   ]
     *  ]
     * ]
     * </pre>
     *
     * @return string|array
     *
     * @throws \Exception
     */
    function upload($request, $directory, $oldFiles = [], $options = []) {

        /** Remove current file if exist. */
        if (count($oldFiles)) {
            foreach ($oldFiles as $one) {
                delete_file($directory . $one);
            }
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

            //Resize image if required
            if (isset($options['resize']) && count($options['resize'])) {
                /*
                 * Generate new file name for all image resize. Those images
                 * different the suffix that is replaced with '_REPLACE'
                 */
                $resizeFileName = generate_filename($directory, $fileExt, [
                    'prefix' => $newFileNamePrefix,
                    'suffix' => '_REPLACE'
                ]);
                foreach ($options['resize'] as $k => $v) {
                    $suffix        = ($newFileNameSuffix !== '') ? $newFileNameSuffix : '_' . $k;
                    $newNameBySize = str_replace('_REPLACE', $suffix, $resizeFileName);

                    if (resize_image($directory . $newFileName, $v['width'], $v['height'],
                                     $directory . $newNameBySize)) {
                        $newFiles[$k] = $newNameBySize;
                    }
                }

                //Delete the original image
                if (count($newFiles)) {
                    delete_file($directory . $newFileName);
                }
            }
        } catch (Exception $ex) {
            throw new \Exception('Whoop!! Can not upload file. ' . $ex->getMessage());
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
                throw new \Exception('Whoop!! Can not delete file. ' . $ex->getMessage());
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

            try {
                $image = \Intervention\Image\Facades\Image::make($imagePath)->orientate();
                $image->fit($width, $height, function ($constraint) {
                    $constraint->upsize();
                });

                if ($newName !== '') {
                    $image->save($newName);
                }

                $image->save();
            } catch (Exception $ex) {
                throw new \Exception('Whoop!! can not resize image. ' . $ex->getMessage());
            }

            return true;
        }

        return false;
    }

}

if ( ! function_exists('select')) {
    /**
     * Get list area such as: cities, districts, wards,...
     * The final result will be:
     * <pre>
     *  [id => name]
     * </pre>
     *
     * @param \Illuminate\Support\Collection $collection
     */
    function select($collection) {
        $area = $collection->keyBy('id')->toArray();
        foreach ($area as $k => $v) {
            $area[$k] = $v['name'];
        }

        return $area;
    }

}

if ( ! function_exists('locations')) {
    function locations($name = '') {
        //Get table dynamically
        $city       = new \App\Models\City();
        $store      = new \App\Models\Store();
        $cities_tbl = DB::getQueryGrammar()->wrapTable($city->getTable());
        $stores_tbl = DB::getQueryGrammar()->wrapTable($store->getTable());

        $join = DB::table($city->getTable())
                    ->leftJoin($store->getTable(), "{$city->getTable()}.id", '=', "{$store->getTable()}.city_id");

        if ($name !== '') {
            $join->where("{$city->getTable()}.name",'LIKE', "%{$name}%");
        }

        return $join->select(DB::raw("{$cities_tbl}.id, {$cities_tbl}.name, COUNT({$stores_tbl}.id) AS count_store"))
                    ->groupBy("{$city->getTable()}.id")
                    ->get();
    }
}

if ( ! function_exists('current_location')) {
    /**
     * @todo Get current location (city)
     *
     * @return \App\Models\City
     */
    function current_location() {
        $current_id = session(_const('SESSION_LOCATION'), _const('DEFAULT_LOCATION'));
        $location   = \App\Models\City::find($current_id);

        return $location;
    }
}