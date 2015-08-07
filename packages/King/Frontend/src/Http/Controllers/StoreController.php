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
        return view('frontend::store.index', [
            'productCount' => store()->products->count(),
            'products'     => store()->products
        ]);
    }

    /**
     *
     * @param Illuminate\Http\Request $request
     *
     * @return type
     */
    public function ajaxSaveProduct(Request $request) {

        //Only accept ajax request
        if ($request->ajax()) {

            $rules      = $this->_product->getRules();
            $messages   = $this->_product->getMessages();
            $validator  = Validator::make($request->all(), $rules, $messages);
            $validFails = $validator->fails();

            /**
             * Check does product's image exist that a product must has at least
             * one image
             */
            $image1 = $request->get('product_image_1');
            $image2 = $request->get('product_image_2');
            $image3 = $request->get('product_image_3');
            $image4 = $request->get('product_image_4');
            if (empty($image1) && empty($image2) && empty($image3) && empty($image4)) {
                $validator->errors()->add('product_image_1', _t('product_image_req'));
            }

            if ($validFails) {
                return ajax_response([
                    'status'   => _const('AJAX_ERROR'),
                    'messages' => $validator->messages()
                ]);
            }

            /**
             * 1. Get path and params.
             * 2. Copy image from temp folder to product image folder then delete
             * image from temp folder.
             * 3. Validate product' image must has at least one.
             * 4. Get product object.
             *
             */
            try {

                // 1
                $tempPath    = config('front.temp_path');
                $productPath = config('front.product_path');
                $images      = [];

                // 2
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

                // 3
                if ( ! count($images)) {

                    $validator->errors()->add('product_image_1', _t('product_image_req'));

                    return ajax_response([
                        'status'   => _const('AJAX_ERROR'),
                        'messages' => $validator->messages()
                    ]);

                }

                // 4
                if (is_null($request->get('id'))) {
                    $product = new Product();
                } else {

                    $id      = (int) $request->get('id');
                    $product = product($id);

                    if ($product === null) {

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
             * 5. Delete old temporary image
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

                // 5
                $currentImage = $request->get('current_image');
                foreach (['original', 'big', 'thumb'] as $size) {

                    $nameBySize = str_replace(_const('TOBEREPLACED'), "_{$size}", $currentImage);

                    delete_file($tempPath . $nameBySize);
                }

                delete_file($tempPath . $currentImage);

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

    /**
     * Delete product's temporary image when upload image to temp directory
     *
     * @param Illuminate\Http\Request $request
     *
     * @return JSON
     */
    public function ajaxDeleteProductTempImg(Request $request) {

        if ($request->ajax()) {

            try {

                $tempPath = config('front.temp_path');
                foreach ([1, 2, 3, 4] as $one) {
                    $imgToDel = $request->get("product_image_{$one}");
                    if ($imgToDel !== '') {
                        foreach (['original', 'big', 'thumb'] as $size) {
                            $nameBySize = str_replace(_const('TOBEREPLACED'), "_{$size}", $imgToDel);

                            delete_file($tempPath . $nameBySize);
                        }

                        delete_file($tempPath . $imgToDel);
                    }
                }

            } catch (Exception $exc) {

                return ajax_response([
                    'status'   => _const('AJAX_ERROR'),
                    'messages' => _t('opp')
                ]);

            }


            return ajax_response([
                'status'   => _const('AJAX_OK'),
                'messages' => _t('saved_info')
            ]);
        }
    }

    /**
     * Find product by id
     *
     * @param Illuminate\Http\Request $request
     * @param int                     $id
     *
     * @return type
     */
    public function ajaxFindProductById(Request $request, $id) {

        // Only accept AJAX request
        if ($request->ajax()) {

            $id      = (int) $id;
            $product = product($id);

            if ($product === null) {
                return ajax_response([
                    'status'   => _const('AJAX_ERROR'),
                    'messages' => _t('not_found')
                ]);
            }

            // Rebuild product data structure
            $productPath = config('front.product_path');
            $product->images();
            $productRebuild = [
                'id'           => $product->id,
                'name'         => $product->name,
                'price'        => $product->price,
                'old_price'    => $product->old_price,
                'description'  => $product->description,
                'images'       => [
                    'image_1' => ($product->image_1 !== null) ? asset($productPath . $product->image_1->thumb) : '',
                    'image_2' => ($product->image_2 !== null) ? asset($productPath . $product->image_2->thumb) : '',
                    'image_3' => ($product->image_3 !== null) ? asset($productPath . $product->image_3->thumb) : '',
                    'image_4' => ($product->image_4 !== null) ? asset($productPath . $product->image_4->thumb) : '',
                ],
                'lastModified' => $product->updated_at
            ];

            return ajax_response([
                'status' => _const('AJAX_OK'),
                'data'   => $productRebuild
            ]);
        }
    }

}