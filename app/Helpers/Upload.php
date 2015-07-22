<?php

namespace App\Helpers;

use Illuminate\Http\Request;

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
     * List old files will be deleted when upload
     *
     * @var array
     */
    protected $_oldFiles;

    /**
     * New file name prefix
     *
     * @var string
     */
    protected $_prefix;

    /**
     * New file name suffix
     *
     * @var string
     */
    protected $_suffix;

    /**
     * New file name when upload
     *
     * @var string
     */
    protected $_newFileName;


    /**
     * Set file
     *
     * @param string $file
     */
    public function setFile($file) {
        $this->_file = $file;
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
     * Set old files
     *
     * @param array $oldFiles
     */
    public function setOldFiles($oldFiles) {
        $this->_oldFiles = $oldFiles;
    }

    /**
     * Get old files
     *
     * @return array
     */
    public function getOldFiles() {
        return $this->_oldFiles;
    }

    /**
     * Set prefix
     *
     * @param string $prefix
     */
    public function setPrefix($prefix) {
        $this->_prefix = $prefix;
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
     * @param array $suffix
     */
    public function setNewFileName($newFileName) {
        $this->_newFileName = $newFileName;
    }

    /**
     * Get new file name
     *
     * @return string
     */
    public function getNewFileName() {
        return $this->_newFileName;
    }


    public function __construct($file)
    {
        $this->_file = $file;
    }

    public function move() {

        $file        = $this->_file;
        $fileExt     = $file->getClientOriginalExtension();
        $newFileName = generate_filename($this->_directory, $fileExt, [
            'prefix' => $this->_prefix,
            'suffix' => $this->_suffix
        ]);

        try {
            $file->move($this->_directory, $newFileName);
        } catch (Exception $ex) {
            throw new \Exception("Whoop!! Couldn't upload file. {$ex->getMessage()}");
        }

        $this->_newFileName = $this->_directory . $newFileName;

        return $this->_directory . $newFileName;
    }
}
