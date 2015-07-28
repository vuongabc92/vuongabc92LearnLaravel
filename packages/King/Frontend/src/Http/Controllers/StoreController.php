<?php

/**
 * FrontendController
 *
 * @author vuongabc92@gmail.com
 */

namespace King\Frontend\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Helpers\Upload;
use App\Models\Product;

class StoreController extends FrontController
{
    /**
     * @var App\Models\Product
     */
    protected $_product;

    public function __construct(Product $product)
    {
        $this->_product = $product;
    }

    public function index() {
        return view('frontend::store.index');
    }

    public function ajaxSaveProduct(Request $request) {
        //Only accept ajax request
        if ($request->ajax()) {
            $rules     = $this->_product->getRules();
            $messages  = $this->_product->getMessages();
            $validator = Validator::make($request->all(), $rules, $messages);

            //A product must has at least one image.
            $image1 = $request->get('product_image_1');
            $image2 = $request->get('product_image_2');
            $image3 = $request->get('product_image_3');
            $image4 = $request->get('product_image_4');
            if (empty($image1) && empty($image2)
                               && empty($image3)
                               && empty($image4)) {
                $validator->errors()->add('image', _t('product_image_req'));
            }

            if ($validator->fails()) {
                return ajax_upload_response([
                    'status'   => _const('AJAX_ERROR'),
                    'messages' => $validator->messages()
                ]);
            }

            try {
                $tempPath    = config('front.temp_path');
                $productPath = config('front.product_path');
                foreach ([$image1, $image2, $image3, $image4] as $one) {
                    if ( ! empty($one) && check_file($tempPath . $one)) {
                        copy($tempPath . $one, $productPath . $one);
                    }
                }

            } catch (Exception $ex) {
                $validator->errors()->add('image', _t('opp'));

                return ajax_upload_response([
                    'status'   => _const('AJAX_ERROR'),
                    'messages' => $validator->errors()->first()
                ]);
            }
        }
    }


    /**
     * Upload product image
     *
     * @param Illuminate\Http\Request $request
     *
     * return JSON
     */
    public function ajaxAddProductImage(Request $request) {

        if ($request->isMethod('POST')) {
            $productMaxFileSize = _const('PRODUCT_MAX_FILE_SIZE');
            $rules = [
                '__product' => 'required|image|mimes:jpg,png,jpeg,gif|max:' . $productMaxFileSize
            ];

            $messages = [
                '__product.required' => _t('no_file'),
                '__product.image'    => _t('file_not_image'),
                '__product.mimes'    => _t('file_image_mimes'),
                '__product.max'      => _t('avatar_max'),
            ];

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return ajax_upload_response([
                    'status'   => _const('AJAX_ERROR'),
                    'messages' => $validator->errors()->first()
                ]);
            }

            /** Check the order of product image when upload */
            $order       = (int) $request->get('order');
            $orderConfig = config('front.product_img_order');
            if ( ! in_array($order, $orderConfig)) {
                $validator->errors()->add('__product', _t('opp'));

                return ajax_upload_response([
                    'status'   => _const('AJAX_ERROR'),
                    'messages' => $validator->errors()->first()
                ]);
            }

            /**
             * 1. Upload
             * 2. Resize
             * 3. Delete old avatars
             * 4. Save new avatars
             */
            try {

                //1
                $tempPath = config('front.temp_path');
                $upload   = new Upload($request->file('__product'));
                $upload->setDirectory($tempPath);
                $upload->setPrefix(_const('PRODUCT_PREFIX'));
                $upload->setSuffix(_const('ORIGINAL_SUFFIX'));
                $original = $upload->move();

                //2
                $imageResized = $upload->resizeGroup([
                    'thumb' => [
                        'width'  => _const('PRODUCT_THUMB'),
                        'height' => _const('PRODUCT_THUMB')
                    ],
                ]);

            } catch (Exception $ex) {
                $validator->errors()->add('__product', _t('opp'));

                return ajax_upload_response([
                    'status'   => _const('AJAX_OK'),
                    'messages' => $validator->errors()->first()
                ], 500);
            }

            return ajax_upload_response([
                'status'   => _const('AJAX_OK'),
                'messages' => _t('saved_info'),
                'data'     => [
                    'original' => $imageResized['original'],
                    'thumb'    => asset($tempPath . $imageResized['thumb']),
                    'order'    => $order
                ]
            ]);

        }

    }
}