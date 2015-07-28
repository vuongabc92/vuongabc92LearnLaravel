<?php
namespace King\Frontend\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Store;
use App\Models\City;
use App\Models\District;
use App\Models\Ward;
use App\Models\Category;
use App\Helpers\Upload;
use App\Helpers\Upload1;
use App\Helpers\Image;
use Validator;
use Hash;
use DB;

class SettingController extends FrontController
{

    /**
     * @var App\Models\User
     */
    protected $_user;

    /**
     * @var App\Models\Store
     */
    protected $store;

    public function __construct(User $user, Store $store)
    {
        $this->_user  = $user;
        $this->store = $store;
    }

    /**
     * Display account setting page
     *
     * @return response
     */
    public function index()
    {
        return view('frontend::setting.account', ['user' => user()]);
    }

    /**
     * Change the current password to new password
     *
     * @param Illuminate\Http\Request $request
     *
     * @return JSON
     */
    public function ajaxChangePassword(Request $request)
    {
        //Only accept AJAX request
        if ($request->ajax()) {

            $rules = [
                'password'     => 'required',
                'new_password' => 'required|min:6|max:60',
            ];

            $messages = [
                'password.required'     => _t('user_pass_req'),
                'new_password.required' => _t('user_pass_req'),
                'new_password.min'      => _t('user_pass_min'),
                'new_password.max'      => _t('user_pass_max'),
            ];

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return ajax_response([
                    'status'   => _const('AJAX_ERROR'),
                    'messages' => $validator->messages()
                ]);
            }

            $user     = user();
            $password = $request->get('password');
            $newPass  = $request->get('new_password');

            /** Check password confirmation */
            if (Hash::check($password, $user->password)) {

                $user->password = bcrypt($newPass);

                try {
                    $user->save();
                } catch (Exception $ex) {
                    return ajax_response([
                        'status'   => _const('AJAX_ERROR'),
                        'messages' => _t('opp')
                    ], 500);
                }

                return ajax_response([
                    'status'   => _const('AJAX_OK'),
                    'messages' => _t('saved_pass')
                ]);
            }

            $validator->errors()->add('password', _t('pass_wrong'));

            return ajax_response([
                'status'   => _const('AJAX_ERROR'),
                'messages' => $validator->messages()
            ]);
        }
    }

    /**
     * Change basic info
     *
     * @param Illuminate\Http\Request $request
     *
     * @return JSON
     */
    public function ajaxSaveBasicInfo(Request $request)
    {
        //Only accept ajax request.
        if ($request->ajax()) {

            $rules           = remove_rules($this->_user->getRules(), 'password.min:6');
            $messages        = $this->_user->getMessages();

            $user            = user();
            $currentUsername = $user->user_name;
            $currentEmail    = $user->email;
            $hashingPass     = $user->password;

            $username        = $request->get('user_name');
            $email           = $request->get('email');
            $fname           = $request->get('first_name');
            $lname           = $request->get('last_name');
            $password        = $request->get('password');
            $checkPass       = false;

            /**
             * Check whether current user name in DB matchs to user name
             * from user input. If does, remove validate unique, otherwise
             * will check password to ensure the user change this info
             * is the owner.
             */
            if (str_equal($currentUsername, $username)) {
                $rules = remove_rules($rules, 'user_name.unique:users,user_name');
            } else {
                $checkPass = true;
            }

            //Email is same with username.
            if (str_equal($currentEmail, $email)) {
                $rules = remove_rules($rules, 'email.unique:users,email');
            } else {
                $checkPass = true;
            }

            if ( ! $checkPass) {
                $rules = remove_rules($rules, 'password');
            }

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return ajax_response([
                    'status'   => _const('AJAX_ERROR'),
                    'messages' => $validator->messages()
                ]);
            }

            //Check does password from input match to password in DB.
            if ($checkPass && ! Hash::check($password, $hashingPass)) {
                $validator->errors()->add('password', _t('pass_incorrect'));
                return ajax_response([
                    'status'   => _const('AJAX_ERROR'),
                    'messages' => $validator->messages()
                ]);
            }

            $user->user_name  = $username;
            $user->email      = $email;
            $user->first_name = $fname;
            $user->last_name  = $lname;

            try {
                $user->save();
            } catch (Exception $ex) {
                return ajax_response([
                    'status'   => _const('AJAX_ERROR'),
                    'messages' => _t('opp')
                ], 500);
            }

            return ajax_response([
                'status'   => _const('AJAX_OK'),
                'messages' => _t('saved_info')
            ]);
        }
    }

    /**
     * Change user avatar
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return JSON
     */
    public function ajaxChangeAvatar(Request $request) {

        if ($request->isMethod('POST')) {

            $avatarMaxFileSize = _const('AVATAR_MAX_FILE_SIZE');
            $rules = [
                '__file' => 'required|image|mimes:jpg,png,jpeg,gif|max:' . $avatarMaxFileSize
            ];

            $messages = [
                '__file.required' => _t('no_file'),
                '__file.image'    => _t('file_not_image'),
                '__file.mimes'    => _t('file_image_mimes'),
                '__file.max'      => _t('avatar_max'),
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


                $avatarPath = config('front.avatar_path');
                $upload     = new Upload1($request->file('__file'));
                $upload->setDirectory($avatarPath);
                $uploadImage = $upload->move();
                $image = new Image($uploadImage);

                $newFileName = generate_filename($avatarPath, $upload->getFileExt(), [
                    'prefix' => _const('AVATAR_PREFIX'),
                    'suffix' => _const('TOBEREPLACED')
                ]);

                $abc = $image->group([
                    'directory' => $avatarPath,
                    'name' => $newFileName,
                    'sizes' => [
                        'big' => [
                            'width'  => _const('AVATAR_BIG'),
                            'height' => _const('AVATAR_BIG')
                        ],
                        'medium' => [
                            'width'  => _const('AVATAR_MEDIUM'),
                            'height' => _const('AVATAR_MEDIUM')
                        ],
                        'small' => [
                            'width'  => _const('AVATAR_SMALL'),
                            'height' => _const('AVATAR_SMALL')
                        ]
                    ]
                ]);

                var_dump($abc);die;
                //2
                $imageResized = $upload->resizeGroup([
                    'big' => [
                        'width'  => _const('AVATAR_BIG'),
                        'height' => _const('AVATAR_BIG')
                    ],
                    'medium' => [
                        'width'  => _const('AVATAR_MEDIUM'),
                        'height' => _const('AVATAR_MEDIUM')
                    ],
                    'small' => [
                        'width'  => _const('AVATAR_SMALL'),
                        'height' => _const('AVATAR_SMALL')
                    ]
                ]);




                die;


                //1
                $user       = user();
                $avatarPath = config('front.avatar_path');
                $upload     = new Upload($request->file('__file'));

                $upload->setDirectory($avatarPath);
                $upload->setPrefix(_const('AVATAR_PREFIX'));
                $upload->setSuffix(_const('ORIGINAL_SUFFIX'));
                $upload->move();

                //2
                $imageResized = $upload->resizeGroup([
                    'big' => [
                        'width'  => _const('AVATAR_BIG'),
                        'height' => _const('AVATAR_BIG')
                    ],
                    'medium' => [
                        'width'  => _const('AVATAR_MEDIUM'),
                        'height' => _const('AVATAR_MEDIUM')
                    ],
                    'small' => [
                        'width'  => _const('AVATAR_SMALL'),
                        'height' => _const('AVATAR_SMALL')
                    ]
                ]);

                //3
                delete_file([
                    $avatarPath . $user->avatar_original,
                    $avatarPath . $user->avatar_big,
                    $avatarPath . $user->avatar_medium,
                    $avatarPath . $user->avatar_small
                ]);

                //4
                $user->avatar_original = $imageResized['original'];
                $user->avatar_big      = $imageResized['big'];
                $user->avatar_medium   = $imageResized['medium'];
                $user->avatar_small    = $imageResized['small'];
                $user->update();

            } catch (Exception $ex) {
                $validator->errors()->add('__file', _t('opp'));

                return ajax_upload_response([
                    'status'   => _const('AJAX_OK'),
                    'messages' => $validator->errors()->first()
                ], 500);
            }

            return ajax_upload_response([
                'status'   => _const('AJAX_OK'),
                'messages' => _t('saved_info'),
                'data'     => [
                    'big'     => asset($avatarPath . $imageResized['big']),
                    'medium'  => asset($avatarPath . $imageResized['medium']),
                    'small'   => asset($avatarPath . $imageResized['small']),
                ]
            ]);
        }
    }

    /**
     * Display save store page
     *
     * @return response
     */
    public function store() {
        $cities     = select(City::select('id', 'name')->get());
        $districts  = ['' => _t('select_district')];
        $wards      = ['' => _t('select_ward')];
        $categories = select(Category::select('id', 'name')->get());
        $store      = store();

        if (user()->has_store) {
            $districts += select($this->getDistrictsByCityId($store->city_id)->keyBy('id'));
            $wards     += select($this->getWardsByCityId($store->district_id)->keyBy('id'));
        }

        return view('frontend::setting.store', [
            'categories' => ['' => _t('select_category')] + $categories,
            'cities'     => ['' => _t('select_city')] + $cities,
            'districts'  => $districts,
            'wards'      => $wards,
            'store'      => $store
        ]);
    }

    /**
     * Get the list districts by city id
     *
     * @param \Illuminate\Http\Request $request
     * @param App\Models\District      $district
     * @param int                      $id
     *
     * @return JSON
     */
    public function ajaxGetDistrictByCityId(Request $request, District $district, $id) {
        //Only accept AJAX request
        if ($request->ajax()) {
            if (City::find((int) $id) !== null) {
                $districts = $this->getDistrictsByCityId($id);

                return ajax_response([
                    'status' => _const('AJAX_OK'),
                    'data'   => $districts->toArray()
                ]);
            }

            return ajax_response([
                'status'   => _const('AJAX_ERROR'),
                'messages' => _t('opp')
            ]);
        }

    }

    /**
     * Get the list wards by ditrict id
     *
     * @param \Illuminate\Http\Request $request
     * @param App\Models\Ward          $ward
     * @param int                      $id
     *
     * @return JSON
     */
    public function ajaxGetWardByCityId(Request $request, Ward $ward, $id) {
        //Only accept AJAX request
        if ($request->ajax()) {
            if (District::find((int) $id) !== null) {
                $wards = $this->getWardsByCityId($id);

                return ajax_response([
                    'status' => _const('AJAX_OK'),
                    'data'   => $wards->toArray()
                ]);
            }

            return ajax_response([
                'status'   => _const('AJAX_ERROR'),
                'messages' => _t('opp')
            ]);
        }

    }


    public function ajaxSaveStoreInfo(Request $request) {
        //Only accept AJAX request
        if ($request->ajax()) {
            $store = $this->store;
            $user  = user();
            if ($user->has_store) {
                $store = store();
            }

            $rules     = $store->getRules();
            $messages  = $store->getMessages();
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return ajax_response([
                    'status'   => _const('AJAX_ERROR'),
                    'messages' => $validator->messages()
                ]);
            }

            try {
                $store->user_id      = $user->id;
                $store->name         = $request->get('name');
                $store->category_id  = $request->get('category_id');
                $store->street       = $request->get('street');
                $store->city_id      = $request->get('city_id');
                $store->district_id  = $request->get('district_id');
                $store->ward_id      = $request->get('ward_id');
                $store->phone_number = $request->get('phone_number');
                if ($store->save()) {
                    $user->has_store = true;
                    $user->update();
                }
            } catch (Exception $ex) {
                $validator->errors()->add('name', _t('opp'));

                return ajax_response([
                    'status'   => _const('AJAX_ERROR'),
                    'messages' => $validator->messages()
                ]);
            }

            return ajax_response([
                'status'   => _const('AJAX_OK'),
                'messages' => _t('saved_info')
            ]);
        }
    }

    /**
     * Get districts that belong to the city/province by city_id
     *
     * @param int $id City id
     *
     * @return \Illuminate\Support\Collection $collection
     */
    public function getDistrictsByCityId($id) {

        $dbRaw     = DB::raw("id, CONCAT(type, ' ', name) as name");
        $districts = District::where('city_id', $id)->select($dbRaw)
                                                    ->orderBy('name')
                                                    ->get();

        return $districts;
    }

    /**
     * Get districts that belong to the city/province by city_id
     *
     * @param int $id City id
     *
     * @return \Illuminate\Support\Collection $collection
     */
    public function getWardsByCityId($id) {

        $dbRaw = DB::raw("id, CONCAT(type, ' ', name) as name");
        $wards = Ward::where('district_id', $id)->select($dbRaw)
                                                ->orderBy('name')
                                                ->get();

        return $wards;
    }

    public function ajaxChangeCover(Request $request) {
        if ($request->isMethod('POST') && user()->has_store) {

            $coverMaxFileSize = _const('COVER_MAX_FILE_SIZE');
            $rules            = [
                '__file' => 'required|image|mimes:jpg,png,jpeg,gif|max:' . $coverMaxFileSize
            ];

            $messages = [
                '__file.required' => _t('no_file'),
                '__file.image'    => _t('file_not_image'),
                '__file.mimes'    => _t('file_image_mimes'),
                '__file.max'      => _t('cover_max'),
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
             * 3. Delete old covers
             * 4. Save new covers
             */
            try {

                //1
                $store     = store();
                $coverPath = config('front.cover_path');
                $upload    = new Upload($request->file('__file'));

                $upload->setDirectory($coverPath);
                $upload->setPrefix(_const('COVER_PREFIX'));
                $upload->setSuffix(_const('ORIGINAL_SUFFIX'));
                $upload->move();

                //2
                $imageResized = $upload->resizeGroup([
                    'big' => [
                        'width'  => _const('COVER_BIG_W'),
                        'height' => _const('COVER_BIG_H')
                    ],
                    'medium' => [
                        'width'  => _const('COVER_MEDIUM_W'),
                        'height' => _const('COVER_MEDIUM_H')
                    ],
                    'small' => [
                        'width'  => _const('COVER_SMALL_W'),
                        'height' => _const('COVER_SMALL_H')
                    ],
                ]);

                //3
                delete_file([
                    $coverPath . $store->cover_original,
                    $coverPath . $store->cover_big,
                    $coverPath . $store->cover_medium,
                    $coverPath . $store->cover_small,
                ]);

                //4
                $store->cover_original = $imageResized['original'];
                $store->cover_big      = $imageResized['big'];
                $store->cover_medium   = $imageResized['medium'];
                $store->cover_small    = $imageResized['small'];
                $store->update();

            } catch (Exception $ex) {
                $validator->errors()->add('__file', _t('opp'));

                return ajax_upload_response([
                    'status'   => _const('AJAX_OK'),
                    'messages' => $validator->errors()->first()
                ], 500);
            }

            return ajax_upload_response([
                'status'   => _const('AJAX_OK'),
                'messages' => _t('saved_info'),
                'data'     => [
                    'big'    => asset($coverPath . $imageResized['big']),
                    'medium' => asset($coverPath . $imageResized['medium']),
                    'small'  => asset($coverPath . $imageResized['small']),
                ]
            ]);
        }
    }
}
