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

            /**
             * 1. Upload
             * 2. Resize
             * 3. Delete old avatars
             * 4. Save new avatars
             */
            try {

                //1
                $user       = user();
                $avatarPath = config('front.product_path');
                $upload     = new Upload($request->file('__product'));
                $upload->setDirectory($avatarPath);
                $upload->setPrefix(_const('PRODUCT_PREFIX'));
                $upload->setSuffix(_const('ORIGINAL_SUFFIX'));
                $upload->move();

                //2
                $imageResized = $upload->resizeGroup([
                    'big' => [
                        'width'  => _const('PRODUCT_BIG'),
                        'height' => _const('PRODUCT_BIG')
                    ],
                    'thumb' => [
                        'width'  => _const('PRODUCT_THUMB'),
                        'height' => _const('PRODUCT_THUMB')
                    ],
                ]);
                $upload->deleteOriginalImage();
//
//                //3
//                delete_file([
//                    $avatarPath . $user->avatar_original,
//                    $avatarPath . $user->avatar_big,
//                    $avatarPath . $user->avatar_medium,
//                    $avatarPath . $user->avatar_small
//                ]);
//
//                //4
//                $user->avatar_original = $imageResized['original'];
//                $user->avatar_big      = $imageResized['big'];
//                $user->avatar_medium   = $imageResized['medium'];
//                $user->avatar_small    = $imageResized['small'];
//                $user->update();

            } catch (Exception $ex) {
                $validator->errors()->add('__file', _t('opp'));

                return ajax_upload_response([
                    'status'   => _const('AJAX_OK'),
                    'messages' => $validator->errors()->first()
                ], 500);
            }

//            return ajax_upload_response([
//                'status'   => _const('AJAX_OK'),
//                'messages' => _t('saved_info'),
//                'data'     => [
//                    'big'     => asset($avatarPath . $imageResized['big']),
//                    'medium'  => asset($avatarPath . $imageResized['medium']),
//                    'small'   => asset($avatarPath . $imageResized['small']),
//                ]
//            ]);

        }

    }
}