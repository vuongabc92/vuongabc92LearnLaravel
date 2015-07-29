<?php

namespace App\Helpers;

use Intervention\Image\Facades\Image as ImageIntervention;

class Image
{
    /**
     * Image path
     *
     * @var string
     */
    protected $_image;

    /**
     * List of image that was resized
     *
     * @var array
     */
    protected $_resizes;

    /**
     * Directory that contain image will be resized
     *
     * @var string
     */
    protected $_directory;

    /**
     * Set image
     *
     * @param array $image
     */
    public function setImage($image) {

        $this->_image = $image;

        return $this;
    }

    /**
     * get image
     *
     * @param array
     */
    public function getImage() {

        return $this->_image;
    }

    /**
     * Set group
     *
     * @param array $resizes
     */
    public function setResizes($resizes) {

        $this->_resizes = $resizes;

        return $this;
    }

    /**
     * get group
     *
     * @param array
     */
    public function getResizes() {

        return $this->_resizes;
    }

    /**
     * Set group
     *
     * @param array $directory
     */
    public function setDirectory($directory) {

        $this->_directory = $directory;

        return $this;
    }

    /**
     * get group
     *
     * @param array
     */
    public function getDirectory() {

        return $this->_directory;
    }

    /**
     * Constructor
     *
     * @param string $image
     */
    public function __construct($image)
    {
        $this->_image = $image;
    }

    /**
     * Resize image to present size
     *
     * @param int    $width
     * @param int    $height
     * @param string $name
     *
     * @return string
     *
     * @throws \Exception
     */
    public function resize($width, $height, $name = '') {

        try {

            $image = ImageIntervention::make($this->_image)->orientate();

            //Won't resize when no size present
            if ($width && $height) {
                $image->fit($width, $height, function ($constraint) {
                    $constraint->upsize();
                });
            }

            if (empty($name)) {
                $image->save();
            } else {
                $image->save($name);
            }

        } catch (Exception $ex) {
            throw new \Exception("Whoop!! Couldn't resize image. {$ex->getMessage()}");
        }

        return (empty($name)) ? $this->_image : $name;

    }

    /**
     * Resize original file to group of size present
     *
     * @param array $group
     *
     * @return \App\Helpers\Image
     */
    public function resizeGroup($group) {

        $resizes = [];
        if (count($group)) {
            foreach ($group as $k => $size) {
                $name = isset($size['name']) ? $this->_directory . $size['name'] : '';
                if ($this->resize($size['width'], $size['height'], $name)) {
                    $resizes[$k] = $size['name'];
                }
            }
        }

        $this->_resizes = $resizes;

        return $this;
    }

}
