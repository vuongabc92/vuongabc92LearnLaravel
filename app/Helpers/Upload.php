<?php

namespace App\Helpers;

use Illuminate\Http\Request;
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

            try {
                if ($imagePath === '') {
                    $imagePath = $this->_directory . $this->_newFileName;
                }
                $image = Image::make($imagePath)->orientate();
                $image->fit($width, $height, function ($constraint) {
                    $constraint->upsize();
                });

                if ($newName !== '') {
                    $image->save($newName);
                }

                $image->save();
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
        
        if (count($sizes)) {
            
            $resized     = [];
            
            if ($newFileName === '') {
                $newFileName = generate_filename($this->_directory, $this->_fileExt, [
                    'prefix' => $this->_prefix,
                    'suffix' => _const('TOBEREPLACED')
                ]);
            }
            
            foreach ($sizes as $k => $size) {
                $nameBySize = str_replace('_ToBeReplaced', "_{$k}", $newFileName);
                if ($this->resize($size['width'], $size['height'], $imagePath, $this->_directory . $nameBySize)) {
                    $resized[$k] = $nameBySize;
                }
            }
            
            try {
                $newOriginnalName = str_replace('_ToBeReplaced', _const('AVATAR_ORIGINAL'), $newFileName);
                $originnalImage   = Image::make($this->_directory . $this->_newFileName)->orientate();
                $originnalImage->save($this->_directory . $newOriginnalName);
                $resized['original'] = $newOriginnalName;
                delete_file($this->_directory . $this->_newFileName);
            } catch (Exception $ex) {
                throw new \Exception("Whoop!! Couldn't update original image. {$ex->getMessage()}");
            }
            
            return $resized;
        }
        
        return [];
    }
    
}
