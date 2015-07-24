<?php

namespace App\Helpers;

use Intervention\Image\Facades\Image;

class Upload {

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
     * @param array $newFileName
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

        $this->_newFileName = $newFileName;

        return $this->_directory . $newFileName;
    }

    /**
     * Resize image
     *
     * @param int    $width
     * @param int    $height
     * @param string $imagePath
     * @param string $newName
     *
     * @return boolean
     * @throws \Exception
     */
    public function resize($width, $height, $imagePath = '', $newName = '') {

        //Only resize when the width and height is specified.
        if ($width && $height) {

            /**
             * 1. Path to image
             * 2. Open image and resize
             * 3. Save image
             */
            try {

                //1
                if ($imagePath === '') {
                    $imagePath = $this->_directory . $this->_newFileName;
                }

                //2
                $image = Image::make($imagePath)->orientate();
                $image->fit($width, $height, function ($constraint) {
                    $constraint->upsize();
                });

                //3
                if ($newName !== '') {
                    $image->save($newName);
                } else {
                    $image->save();
                }

            } catch (Exception $ex) {
                throw new \Exception("Whoop!! Couldn't resize image. {$ex->getMessage()}");
            }

            return true;
        }

        return false;
    }

    /**
     * Resize group of images
     *
     * @param array  $sizes
     * @param string $newFileName
     * @param string $imagePath
     *
     * @return type
     * @throws \Exception
     */
    public function resizeGroup($sizes, $newFileName = '', $imagePath = '') {

        /**
         * 1. Get params neccessary
         * 2. Generate new file name for group images will be resized
         * 3. Resize images with appropriate name by size suffix
         * 4. Rename the original image that was upload at the begin
         * same with step 2
         */
        if (count($sizes)) {

            //1
            $resized      = [];
            $directory    = $this->_directory;
            $imgUpload    = $this->_newFileName;
            $toBeReplaced = _const('TOBEREPLACED');
            $original     = _const('ORIGINAL_SUFFIX');

            //2
            if ($newFileName === '') {
                $newFileName = generate_filename($directory, $this->_fileExt, [
                    'prefix' => $this->_prefix,
                    'suffix' => $toBeReplaced
                ]);
            }

            //3
            foreach ($sizes as $k => $size) {
                $nameBySize = str_replace($toBeReplaced, "_{$k}", $newFileName);
                if ($this->resize($size['width'], $size['height'], $imagePath, $directory . $nameBySize)) {
                    $resized[$k] = $nameBySize;
                }
            }

            //4
            try {
                $newOriginalName = str_replace($toBeReplaced, $original, $newFileName);
                $originnalImage   = Image::make($directory . $imgUpload)->orientate();
                $originnalImage->save($directory . $newOriginalName);
                $resized['original'] = $newOriginalName;
                $this->_newFileName  = $newOriginalName;
                delete_file($directory . $imgUpload);
            } catch (Exception $ex) {
                throw new \Exception("Whoop!! Couldn't update original image. {$ex->getMessage()}");
            }

            return $resized;
        }

        return [];
    }

    /**
     * Delete original image that was upload at the begin or
     * was changed the name when resize group
     *
     * @throws \Exception
     */
    public function deleteOriginalImage() {
        try {

            delete_file($this->_directory . $this->_newFileName);

        } catch (Exception $ex) {
            throw new \Exception("Whoop!! Couldn't delete original image. {$ex->getMessage()}");
        }
    }

}
