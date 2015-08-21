<?php

/**
 * FrontendController
 *
 * @author vuongabc92@gmail.com
 */

namespace King\Frontend\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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
    
    /**
     * Product image sizes type thumb, big, ...
     * 
     * @var array
     */
    protected $_productImgSizes;

    public function __construct(Product $product)
    {
        $this->_product         = $product;
        $this->_productImgSizes = config('front.product_img_size');
    }

    /**
     * Display store page
     *
     * @return response
     */
    public function index() {
        return view('frontend::store.index', [
            'productCount' => store()->products->count(),
            'products'     => store()->products
        ]);
    }

    /**
     * Save product info
     * 
     * @param Illuminate\Http\Request $request
     *
     * @return type
     */
    public function ajaxSaveProduct(Request $request) {

        //Only accept ajax request
        if ($request->ajax()) {

            $productId = (int) $request->get('id');
            $product   = $this->_getProduct($productId);

            $rules     = $this->_product->getRules();
            $messages  = $this->_product->getMessages();

            if ($productId) {
                $rules = remove_rules($rules, 'product_image_1');
            }

            $validator  = Validator::make($request->all(), $rules, $messages);

            $tempImages = [
                $request->get('product_image_1'),
                $request->get('product_image_2'),
                $request->get('product_image_3'),
                $request->get('product_image_4')
            ];

            if (is_null($product)) {
                $validator->after(function($validator) {
                    $validator->errors()->add('product_image_1', _t('not_found'));
                });
            }

            if ($validator->fails()) {
                return ajax_response([
                    'status'   => _const('AJAX_ERROR'),
                    'messages' => $validator->messages()
                ], is_null($product) ? 404 : 500);
            }


            /**
             *  1. Copy product images from temporary folder to product folder.
             *  2. Delete old product image(s).
             *  3. Save product.
             */
            try {

                // 1
                $images = $this->_copyTempProductImages($tempImages);

                // 2
                if ($productId) {
                    $this->_deleteOldImages($images, $product->images);
                }

                // 3
                $product->store_id    = store()->id;
                $product->name        = $request->get('name');
                $product->price       = $request->get('price');
                $product->old_price   = $request->get('old_price');
                $product->description = $request->get('description');
                $product->setImages($images);
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
    public function ajaxUploadProductImage(Request $request) {

        if ($request->isMethod('POST')) {

            $order     = (int) $request->get('order');
            $rules     = $this->_getProductImageRules();
            $messages  = $this->_getProductImageMessages();
            $validator = Validator::make($request->all(), $rules, $messages);

            // Check does the product image's order exist
            if ( ! $this->_checkProductImageOrder($order)) {
                $validator->after(function($validator) {
                    $validator->errors()->add('__product', _t('opp'));
                });
            }

            if ($validator->fails()) {
                return ajax_upload_response([
                    'status'   => _const('AJAX_ERROR'),
                    'messages' => $validator->errors()->first()
                ], 500);
            }

            try {

                $file         = $request->file('__product');
                $currentImage = $request->get('current_image');
                $upload       = $this->_uploadProductImage($file, $currentImage);

            } catch (Exception $ex) {

                $validator->errors()->add('__product', _t('opp'));

                return ajax_upload_response([
                    'status'   => _const('AJAX_OK'),
                    'messages' => $validator->errors()->first()
                ], 500);
            }

            $resizes = $upload['image']->getResizes();

            return ajax_upload_response([
                'status'   => _const('AJAX_OK'),
                'messages' => _t('saved_info'),
                'data'     => [
                    'original' => $upload['filename']->getName(),
                    'thumb'    => asset($upload['temp_path'] . $resizes['thumb']),
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

        //Only accept ajax request
        if ($request->ajax()) {

            try {

                $this->_deleteProductTempImg($request);

            } catch (Exception $ex) {

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

        // Only accept AJAX with HTTP GET request
        if ($request->ajax() && $request->isMethod('GET')) {

            $id      = (int) $id;
            $product = product($id);

            if ($product === null) {
                return ajax_response([
                    'status'   => _const('AJAX_ERROR'),
                    'messages' => _t('not_found')
                ], 404);
            }
            
            $productRebuild = $this->_rebuildProductData($product);

            return ajax_response([
                'status' => _const('AJAX_OK'),
                'data'   => $productRebuild
            ]);
        }
    }
    


    public function ajaxDeleteProduct(Request $request) {

        // Only accept ajax request with method is delete
        if ($request->ajax() && $request->isMethod('DELETE')) {

        }
    }

    /**
     * Get product entity
     *
     * @param int $id Product id
     *
     * @return App\Models\Product
     */
    protected function _getProduct($id = 0) {

        if ($id) {
            $product = product($id);
        } else {
            $product = new Product();
        }

        return $product;
    }

    /**
     * Copy temporary product image from temp folder to product folder
     * and delete images on temp folder
     *
     * @param array $tempImages Temporary product image that was updated
     * to temp folder
     *
     * @return Illuminate\Support\Collection
     */
    protected function _copyTempProductImages($tempImages) {

        $tempPath    = config('front.temp_path');
        $productPath = config('front.product_path');
        $images      = [];

        if (count($tempImages)) {

            foreach ($tempImages as $k => $image) {

                $imageSize = [];

                if (check_file($tempPath . $image) && count($this->_productImgSizes)) {

                    foreach ($this->_productImgSizes as $size) {

                        $nameBySize = str_replace(_const('TOBEREPLACED'), "_{$size}", $image);
                        if (copy($tempPath . $nameBySize, $productPath . $nameBySize)) {
                            $imageSize[$size] = $nameBySize;
                        }

                        delete_file($tempPath . $nameBySize);
                    }

                    delete_file($tempPath . $image);
                }

                if (count($imageSize)) {
                    $images[$k] = $imageSize;
                }
            }
        }

        return new Collection($images);
    }

    /**
     * Delete product old images
     *
     * @param Illuminate\Support\Collection $newImages
     * @param array                         $oldImages
     *
     * @return void
     */
    protected function _deleteOldImages($newImages, $oldImages) {

        $oldImages   = new Collection(json_decode($oldImages));
        $productPath = config('front.product_path');

        if ($oldImages->count()) {
            foreach ($newImages as $k => $image) {
                if (isset($oldImages[$k])) {
                    foreach ($oldImages[$k] as $one) {
                        delete_file($productPath . $one);
                    }
                }
            }
        }
    }

    /**
     * Get product image rules
     *
     * @return array
     */
    protected function _getProductImageRules() {

        $maxFileSize = _const('PRODUCT_MAX_FILE_SIZE');

        return [
            '__product' => 'required|image|mimes:jpg,png,jpeg,gif|max:' . $maxFileSize
        ];
    }

    /**
     * Get product image rule messages
     *
     * @return array
     */
    protected function _getProductImageMessages() {

        return [
            '__product.required' => _t('no_file'),
            '__product.image'    => _t('file_not_image'),
            '__product.mimes'    => _t('file_image_mimes'),
            '__product.max'      => _t('avatar_max'),
        ];
    }

    /**
     * Check does the image order exist
     *
     * @param int $order product image order
     *
     * @return boolean
     */
    protected function _checkProductImageOrder($order) {

        $orderConfig = (array) config('front.product_img_order');

        if ( ! in_array($order, $orderConfig)) {

            return false;
        }

        return true;
    }

    /**
     * Upload and resize product image
     *
<<<<<<< HEAD
     * 1. Get path and file upload
=======
     * 1. Get path
>>>>>>> 2c1f0bdafd78d5faae8770660083855c33cb6275
     * 2. Generate file name
     * 3. Upload
     * 4. Resize
     * 5. Delete old temporary image(s)
     *
<<<<<<< HEAD
     * @param Illuminate\Http\Request $request
=======
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile|array $file
     * @param string                                                    $currentImage
>>>>>>> 2c1f0bdafd78d5faae8770660083855c33cb6275
     *
     * @return array
     */
    protected function _uploadProductImage($file, $currentImage) {

        // 1
        $tempPath = config('front.temp_path');

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
        foreach ($this->_productImgSizes as $size) {

            $nameBySize = str_replace(_const('TOBEREPLACED'), "_{$size}", $currentImage);

            delete_file($tempPath . $nameBySize);
        }

        delete_file($tempPath . $currentImage);

        return [
            'image'     => $image,
            'temp_path' => $tempPath,
            'filename'  => $filename
        ];
    }
    
    /**
     * Delete product temporary images that was uploaded to temp folder
     * 
     * @param Illuminate\Http\Request $request
     * 
     * @return void
     */
    protected function _deleteProductTempImg($request) {
        
        $tempPath = config('front.temp_path');

        foreach ([1, 2, 3, 4] as $one) {

            $imgToDel = $request->get("product_image_{$one}");

            if ($imgToDel !== '' && check_file($tempPath . $imgToDel)) {
                
                foreach ($this->_productImgSizes as $size) {
                    
                    $nameBySize = str_replace(_const('TOBEREPLACED'), "_{$size}", $imgToDel);

                    delete_file($tempPath . $nameBySize);
                }

                delete_file($tempPath . $imgToDel);
            }
        }
    }
    
    /**
     * Rebuild product data, only get necessary infos
     * 
     * @param App\Models\Product $product
     * 
     * @return array
     */
    protected function _rebuildProductData($product) {
        
        $productPath = config('front.product_path');
        $product->toImage();

        return [
            'id'          => $product->id,
            'name'        => $product->name,
            'price'       => $product->price,
            'old_price'   => $product->old_price,
            'description' => $product->description,
            'images'      => [
                'image_1' => ($product->image_1 !== null) ? asset($productPath . $product->image_1->thumb) : '',
                'image_2' => ($product->image_2 !== null) ? asset($productPath . $product->image_2->thumb) : '',
                'image_3' => ($product->image_3 !== null) ? asset($productPath . $product->image_3->thumb) : '',
                'image_4' => ($product->image_4 !== null) ? asset($productPath . $product->image_4->thumb) : '',
            ],
            'lastModified' => $product->updated_at
        ];
    }
}
