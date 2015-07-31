<?php

namespace App\Models;

class Product extends Base
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * Get product validation rules
     *
     * @return array
     */
    public function getRules() {
        return [
            'name'        => 'required|min:6|max:250',
            'price'       => 'required|min:3|max:16',
            'old_price'   => 'min:3|max:16',
            'description' => 'required|min:10'
        ];
    }

    /**
     * Get product validation messages
     *
     * @return array
     */
    public function getMessages() {
        return [
            'name.required'        => _t('product_name_req'),
            'name.min'             => _t('product_name_min'),
            'name.max'             => _t('product_name_max'),
            'price.required'       => _t('product_price_req'),
            'price.min'            => _t('product_price_min'),
            'price.max'            => _t('product_price_max'),
            'old_price.min'        => _t('product_oldprice_min'),
            'old_price.max'        => _t('product_oldprice_max'),
            'description.required' => _t('product_desc_req'),
            'description.min'      => _t('product_desc_min'),
        ];
    }
}
