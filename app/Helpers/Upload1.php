<?php

namespace App\Helpers;

class Upload1 {

    /**
     * File to upload
     *
     * @var \Symfony\Component\HttpFoundation\File\UploadedFile|array
     */
    protected $_file;

    /**
     * File extension
     *
     * @var string
     */
    protected $_fileExt;

    /**
     * Upload directory
     *
     * @var string
     */
    protected $_directory;

    /**
     * Old files
     *
     * @var array
     */
    protected $_oldFiles = [];

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
    protected $_originalFileName;


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
     * Set file extension
     *
     * @param string $fileExt
     */
    public function setFileExt($fileExt) {
        $this->_fileExt = $fileExt;
    }

    /**
     * Get file extension
     *
     * @return string
     */
    public function getFileExt() {
        return $this->_fileExt;
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
     * @param array $originalFileName
     */
    public function setOriginalFileName($originalFileName) {
        $this->_originalFileName = $originalFileName;
    }

    /**
     * Get new file name
     *
     * @return string
     */
    public function getOriginalFileName() {
        return $this->_originalFileName;
    }


    public function __construct($file)
    {
        $this->_file    = $file;
        $this->_fileExt = $file->getClientOriginalExtension();
    }

    /**
     * Upload file to the directory
     *
     * @return string
     * @throws \Exception
     */
    public function move() {

        $file        = $this->_file;
        $newFileName = generate_filename($this->_directory, $this->_fileExt, [
            'prefix' => $this->_prefix,
            'suffix' => $this->_suffix
        ]);

        try {
            $file->move($this->_directory, $newFileName);
        } catch (Exception $ex) {
            throw new \Exception("Whoop!! Couldn't upload file. {$ex->getMessage()}");
        }

        return $this->_originalFileName = $newFileName;
    }

}
