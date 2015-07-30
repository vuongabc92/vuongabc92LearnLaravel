<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class FileName
{

    /**
     * Name's generated
     *
     * @var string
     */
    protected $_name = '';

    /**
     * File directory
     *
     * @var string
     */
    protected $_directory;

    /**
     * File extension
     *
     * @var string
     */
    protected $_extension;

    /**
     * File suffix
     *
     * @var string
     */
    protected $_suffix = '';

    /**
     * File prefix
     *
     * @var string
     */
    protected $_prefix = '';

    /**
     * Random string
     *
     * @var string
     */
    private $_random = '';

    /**
     * Limit character of string file name
     *
     * @var int
     */
    private $_limit = 0;

    /**
     * Id to specify file
     *
     * @var int
     */
    private $_id = 0;

    /**
     * Group sizes
     *
     * @var array
     */
    private $_group = [];

    /**
     * Default limit character of string file name
     */
    const AVATAR_LIMIT  = 12;
    const COVER_LIMIT   = 12;
    const PRODUCT_LIMIT = 16;

    /**
     * Default number string to random
     */
    const RANDOM = 16;

    /**
     * Default number string to random
     */
    const TOBEREPLACED = '_ToBeReplaced';

    /**
     * Default original suffix
     */
    const _ORIGINAL = '_original';

    const ORIGINAL = 'original';

    /**
     * Set directory
     *
     * @param string $name
     *
     * @return \App\Helpers\FileName
     */
    public function setName($name) {

        $this->_name = $name;

        return $this;
    }

    /**
     * Get directory
     *
     * @return string
     */
    public function getName() {

        return $this->_name;
    }

    /**
     * Set directory
     *
     * @param string $directory
     *
     * @return \App\Helpers\FileName
     */
    public function setDirectory($directory) {

        $this->_directory = $directory;

        return $this;
    }

    /**
     * Get directory
     *
     * @return string
     */
    public function getDirectory() {

        return $this->_directory;
    }

    /**
     * Set extension
     *
     * @param string $extension
     *
     * @return \App\Helpers\FileName
     */
    public function setExtension($extension) {

        $this->_extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string
     */
    public function getExtension() {

        return $this->_extension;
    }

    /**
     * Set suffix
     *
     * @param string $suffix
     *
     * @return \App\Helpers\FileName
     */
    public function setSuffix($suffix) {

        $this->_suffix = $suffix;

        return $this;
    }

    /**
     * Get suffix
     *
     * @return string
     */
    public function getSuffix() {

        return $this->_suffix;
    }

    /**
     * Set prefix
     *
     * @param string $prefix
     *
     * @return \App\Helpers\FileName
     */
    public function setPrefix($prefix) {

        $this->_prefix = $prefix;

        return $this;
    }

    /**
     * Get prefix
     *
     * @return string
     */
    public function getPrefix() {

        return $this->_prefix;
    }

    /**
     * Set random
     *
     * @param string $random
     *
     * @return \App\Helpers\FileName
     */
    public function setRandom($random) {

        $this->_random = $random;

        return $this;
    }

    /**
     * Get random
     *
     * @return string
     */
    public function getRandom() {

        return $this->_random;
    }

    /**
     * Set limit
     *
     * @param int $limit
     *
     * @return \App\Helpers\FileName
     */
    public function setLimit($limit) {

        $this->_limit = $limit;

        return $this;
    }

    /**
     * Set limit
     *
     * @return string
     */
    public function getLimit() {

        return $this->_limit;
    }

    /**
     * Set id
     *
     * @param int $id
     *
     * @return \App\Helpers\FileName
     */
    public function setId($id) {

        $this->_id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {

        return $this->_id;
    }

    /**
     * Set group
     *
     * @param int $group
     *
     * @return \App\Helpers\FileName
     */
    public function setGroup($group) {

        $this->_group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return int
     */
    public function getGroup() {

        return $this->_group;
    }

    /**
     * Constructor
     *
     * @param string $directory
     * @param string $extension
     */
    public function __construct($directory, $extension)
    {
        $this->_directory = $directory;
        $this->_extension = $extension;
    }


    /**
     * Generate random file name base on present info
     *
     * @return string
     */
    public function generate() {

        $userId     = ( ! empty($this->_id))     ? $this->_id     : 0;
        $randString = ( ! empty($this->_random)) ? $this->_random : $this->random();
        $microtime  = microtime(true);
        $md5Name    = md5($userId . $microtime . $randString);
        $subName    = $this->limit($md5Name, $this->_limit);
        $name       = $this->_prefix . $subName . $this->_suffix . '.' . $this->_extension;

        while ($this->fileExist($this->_directory . $name)) {
            $name = $this->generate();
        }

        return $this->_name = $name;
    }

    /**
     * Generate group of sizes to resize.
     * Final result will be an array that contain info such as:
     * Final name when resize, width, height.
     *
     * @param array $sizes
     *
     * @return \App\Helpers\FileName
     */
    public function group($sizes, $original = false) {

        $final = [];

        if (count($sizes)) {

            $this->setSuffix(self::TOBEREPLACED);
            $nameGroup = $this->generate();

            foreach ($sizes as $k => $size) {
                $nameBySize = str_replace(self::TOBEREPLACED, "_{$k}", $nameGroup);
                $final[$k]  = [
                    'width'  => $size['width'],
                    'height' => $size['height'],
                    'name'   => $nameBySize,
                ];
            }
        }

        //Generate name for original file that wasn't resized.
        if ($original) {
            $originalName = str_replace(self::TOBEREPLACED, self::_ORIGINAL, $nameGroup);
            $final[self::ORIGINAL] = [
                'width'  => 0,
                'height' => 0,
                'name'   => $originalName,
            ];
        }

        $this->_group = $final;

        return $this;
    }

    /**
     * Set info for create avatar file name
     *
     * @return \App\Helpers\FileName
     */
    public function avatar() {

        $this->_id     = $this->guard()->user()->id;
        $this->_random = $this->random();
        $this->_limit  = self::AVATAR_LIMIT;

        return $this;
    }

    /**
     * Set info for create cover file name
     *
     * @return \App\Helpers\FileName
     */
    public function cover() {

        $this->_id     = $this->guard()->user()->id;
        $this->_random = $this->random();
        $this->_limit  = self::COVER_LIMIT;

        return $this;
    }

    /**
     * Set info for create product file name
     *
     * @return \App\Helpers\FileName
     */
    public function product() {

        $this->_id     = $this->guard()->user()->store->id;
        $this->_random = $this->random();
        $this->_limit  = self::PRODUCT_LIMIT;

        return $this;
    }

    /**
     * Get the available auth instance.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard() {
        return app('Illuminate\Contracts\Auth\Guard');
    }

    /**
     * Check does the present file exist
     *
     * @param string $file Path to file
     *
     * @return boolean
     */
    public function fileExist($file) {

        if ( ! is_dir($file) && file_exists($file)) {
            return true;
        }

        return false;
    }

    /**
     * Generate a more truly "random" alpha-numeric string.
     *
     * @param  int  $length
     * @return string
     *
     * @throws \RuntimeException
     */
    public function random() {
        return Str::random(self::RANDOM);
    }

    /**
     * Limit the number of characters in a string.
     *
     * @param  string  $value
     * @param  int     $limit
     * @param  string  $end
     * @return string
     */
    public function limit($value, $limit = 12, $end = '') {
        return Str::limit($value, $limit, $end);
    }
}
