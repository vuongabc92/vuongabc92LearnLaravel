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
use App\Helpers\FileName;
use App\Helpers\Image;
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

            /**
             * Check product image exist.
             * A product must has at least one image
             */
            $image1 = $request->get('product_image_1');
            $image2 = $request->get('product_image_2');
            $image3 = $request->get('product_image_3');
            $image4 = $request->get('product_image_4');
            if (empty($image1) && empty($image2) && empty($image3) && empty($image4)) {
                $validator->errors()->add('product_image_1', _t('product_image_req'));
            }

            if ($validator->fails()) {
                return ajax_response([
                    'status'   => _const('AJAX_ERROR'),
                    'messages' => $validator->messages()
                ]);
            }

            try {

                $tempPath    = config('front.temp_path');
                $productPath = config('front.product_path');
                $images      = [];

                foreach ([$image1, $image2, $image3, $image4] as $one) {

                    $imageSize   = [];
                    if ( ! empty($one) && check_file($tempPath . $one)) {

                        $toBeReplaced = _const('TOBEREPLACED');

                        foreach (['original', 'big', 'thumb'] as $size) {

                            $nameBySize = str_replace(_const('TOBEREPLACED'), "_{$size}", $one);
                            if (copy($tempPath . $nameBySize, $productPath . $nameBySize)) {
                                $imageSize[$size] = $nameBySize;
                            }

                            delete_file($tempPath . $nameBySize);
                        }

                        delete_file($tempPath . $one);
                    }

                    if (count($imageSize)) {
                        $images[] = $imageSize;
                    }
                }

                if (is_null($request->get('id'))) {

                    $product = new Product();

                } else {

                    $id      = (int) $request->get('id');
                    $product = Product::where('id', $id)->where('store_id', store()->id)->get();

                    if (is_null($product)) {

                        $validator->errors()->add('product_image_1', _t('opp'));

                        return ajax_response([
                            'status'   => _const('AJAX_ERROR'),
                            'messages' => $validator->messages()
                        ]);
                    }
                }

                $product->store_id    = store()->id;
                $product->name        = $request->get('name');
                $product->price       = $request->get('price');
                $product->old_price   = $request->get('old_price');
                $product->description = $request->get('description');
                $product->images      = json_encode($images);
                $product->save();

            } catch (Exception $ex) {

                $validator->errors()->add('product_image_1', _t('opp'));

                return ajax_response([
                    'status'   => _const('AJAX_ERROR'),
                    'messages' => $validator->errors()->first()
                ]);

            }

            return ajax_response([
                'status'   => _const('AJAX_OK'),
                'messages' => _t('saved_info')
            ]);
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

            $rules = [
                '__product' => 'required|image|mimes:jpg,png,jpeg,gif|max:' . _const('PRODUCT_MAX_FILE_SIZE')
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
             * 1. Get path and file
             * 2. Generate file name
             * 3. Upload
             * 4. Resize
             */
            try {

                // 1
                $tempPath = config('front.temp_path');
                $file     = $request->file('__product');

                // 2
                $filename = new FileName($tempPath, $file->getClientOriginalExtension());
                $filename->setPrefix(_const('PRODUCT_PREFIX'))->product()->generate();
                $filename->group([
                    'big' => [
                        'width'  => _const('PRODUCT_BIG'),
                        'height' => _const('PRODUCT_BIG')
                    ],
                    'thumb' => [
                        'width'  => _const('PRODUCT_THUMB'),
                        'height' => _const('PRODUCT_THUMB')
                    ],
                ], true);

                // 3
                $upload = new Upload($file);
                $upload->setDirectory($tempPath)->setName($filename->getName())->move();

                // 4
                $image = new Image($tempPath . $filename->getName());
                $image->setDirectory($tempPath)->resizeGroup($filename->getGroup());

                $currentImage = $request->get('current_image');
                foreach (['original', 'big', 'thumb'] as $size) {

                    $nameBySize = str_replace(_const('TOBEREPLACED'), "_{$size}", $currentImage);

                    delete_file($tempPath . $nameBySize);
                }
                delete_file($tempPath . $request->get('current_image'));

            } catch (Exception $ex) {
                $validator->errors()->add('__product', _t('opp'));

                return ajax_upload_response([
                    'status'   => _const('AJAX_OK'),
                    'messages' => $validator->errors()->first()
                ], 500);
            }

            $resizes = $image->getResizes();

            return ajax_upload_response([
                'status'   => _const('AJAX_OK'),
                'messages' => _t('saved_info'),
                'data'     => [
                    'original' => $filename->getName(),
                    'thumb'    => asset($tempPath . $resizes['thumb']),
                    'order'    => $order
                ]
            ]);

        }

    }

    public function getExtensionFromPath($path) {

        $path = explode('.', $path);

        return (count($path) > 1) ? $path[count($path) - 1] : 'undefined';
    }
}