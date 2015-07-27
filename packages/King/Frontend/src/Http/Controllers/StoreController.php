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
    public function index()
    {
        return view('frontend::store.index');
    }

    public function ajaxSaveProduct(){}


    public function ajaxAddProductImage(Request $request){

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
                $upload->deleteOriginalImage();

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
                    'original' => asset($tempPath . $original),
                    'thumb'    => asset($tempPath . $imageResized['thumb']),
                    'order'    => $order
                ]
            ]);

        }

    }
}