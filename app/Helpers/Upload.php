<?php

namespace App\Helpers;

class Upload {

    /**
     * File to upload
     *
     * @var \Symfony\Component\HttpFoundation\File\UploadedFile|array
     */
    protected $_file;

    /**
     * Upload directory
     *
     * @var string
     */
    protected $_directory;

    /**
     * New file name prefix
     *
     * @var string
     */
    protected $_prefix = '';

    /**
     * New file name suffix
     *
     * @var string
     */
    protected $_suffix = '';

    /**
     * New file name when upload
     *
     * @var string
     */
    protected $_name;


    /**
     * Set file
     *
     * @param string $file
     */
    public function setFile($file) {
        $this->_file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile() {
        return $this->_file;
    }

    /**
     * Set directory
     *
     * @param string $directory
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
     * Set prefix
     *
     * @param string $prefix
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
     * Set suffix
     *
     * @param array $suffix
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
     * Set new file name
     *
     * @param array $name
     */
    public function setName($name) {
        $this->_name = $name;

        return $this;
    }

    /**
     * Get new file name
     *
     * @return string
     */
    public function getName() {
        return $this->_name;
    }

    /**
     * Constructor
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile|array $file
     */
    public function __construct($file)
    {
        $this->_file = $file;
    }

    /**
     * Upload
     *
     * @return string
     * @throws \Exception
     */
    public function move() {

        $file = $this->_file;

        try {
            $file->move($this->_directory, $this->_name);
        } catch (Exception $ex) {
            throw new \Exception("Whoop!! Couldn't upload file. {$ex->getMessage()}");
        }

        return $this;
    }

}
