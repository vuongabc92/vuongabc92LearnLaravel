<?php

namespace App\Models;

use Illuminate\Support\Collection;

class Product extends Base
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * Product image
     *
     * @var string
     */
    public $image_1;
    public $image_2;
    public $image_3;
    public $image_4;

    /**
     * Maximun image of a product
     */
    const MAX_PRODUCT_IMG = 4;

    /**
     * Get product validation rules
     *
     * @return array
     */
    public function getRules() {
        return [
            'name'            => 'required|min:6|max:250',
            'price'           => 'required|min:3|max:16',
            'old_price'       => 'min:3|max:16',
            'description'     => 'required|min:10',
            'product_image_1' => 'required_without_all:product_image_2,product_image_3,product_image_4',
            //'product_image_2' => 'product_image',
            //'product_image_3' => 'product_image',
            //'product_image_4' => 'product_image',
        ];
    }

    /**
     * Get product validation messages
     *
     * @return array
     */
    public function getMessages() {
        return [
            'name.required'                        => _t('product_name_req'),
            'name.min'                             => _t('product_name_min'),
            'name.max'                             => _t('product_name_max'),
            'price.required'                       => _t('product_price_req'),
            'price.min'                            => _t('product_price_min'),
            'price.max'                            => _t('product_price_max'),
            'old_price.min'                        => _t('product_oldprice_min'),
            'old_price.max'                        => _t('product_oldprice_max'),
            'description.required'                 => _t('product_desc_req'),
            'description.min'                      => _t('product_desc_min'),
            'product_image_1.required_without_all' => _t('product_image_req'),
            //'product_image_1.product_image'        => _t('product_image_req'),
            //'product_image_2.product_image'        => _t('product_image_req'),
            //'product_image_3.product_image'        => _t('product_image_req'),
            //'product_image_4.product_image'        => _t('product_image_req'),
        ];
    }

    /**
     * Product images
     *
     * @return \App\Models\Product
     */
    public function toImage() {
        $images        = new Collection(json_decode($this->images));
        $this->images  = $images;
        $this->image_1 = isset($images[0]) ? $images[0] : null;
        $this->image_2 = isset($images[1]) ? $images[1] : null;
        $this->image_3 = isset($images[2]) ? $images[2] : null;
        $this->image_4 = isset($images[3]) ? $images[3] : null;

        return $this;
    }

    /**
     * Set product images
     *
     * @param Illuminate\Support\Collection $images
     *
     * @return \App\Models\Product
     */
    public function setImages($images) {

        $oldImages     = new Collection(json_decode($this->images));
        $maxProductImg = (int) config('front.max_product_img');

        if ($images->count() === $maxProductImg || ( ! $oldImages->count())) {

            $this->images = $images->values()->toJson();

        } else {

            foreach ($images as $k => $image) {
                $oldImages[$k] = $image;
            }

            $this->images = $oldImages->values()->toJson();
        }

        return $this;
    }

}
