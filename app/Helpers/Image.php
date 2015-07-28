<?php

namespace App\Helpers;

use Intervention\Image\Facades\Image as ImageIntervention;

class Image
{
    protected $_toBeReplaced = '_ToBeReplaced';

    protected $_group = [];


    protected $_image;

    public function __construct($image)
    {
        $this->_image = $image;
    }


    public function resize($width, $height, $name = '') {

        if ($width && $height) {

            try {

                $image = ImageIntervention::make($this->_image)->orientate();
                $image->fit($width, $height, function ($constraint) {
                    $constraint->upsize();
                });

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

        return false;
    }

    public function resizeGroup($images) {
        $resizes = [];
        foreach ($images as $k => $image) {
            $name        = isset($image['name']) ? $image['name'] : '';
            $resizes[$k] = $this->resize($image['width'], $image['height'], $name);
        }

        return $resizes;
    }

    public function group($group) {
        $final = [];
        if (count($group)) {
            foreach($group['sizes'] as $k => $size) {
                $nameBySize = str_replace($this->_toBeReplaced, "_{$k}", $group['name']);
                $final[$k]  = [
                    'width'  => $size['width'],
                    'height' => $size['height'],
                    'name'   => $group['directory'] . $nameBySize,
                ];
            }
        }

        return $this->_group = $final;
    }

}
