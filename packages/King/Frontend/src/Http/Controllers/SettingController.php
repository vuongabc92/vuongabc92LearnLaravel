<?php
namespace King\Frontend\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Store;
use App\Models\City;
use App\Models\District;
use App\Models\Ward;
use App\Models\Category;
use Validator;
use Hash;
use DB;

class SettingController extends FrontController
{

    /**
     * @var App\Models\User
     */
    protected $user;

    /**
     * @var App\Models\Store
     */
    protected $store;

    public function __construct(User $user, Store $store)
    {
        $this->user  = $user;
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

            $rules           = remove_rules($this->user->getRules(), 'password.min:6');
            $messages        = $this->user->getMessages();

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
     * @return response
     */
    public function ajaxChangeAvatar(Request $request) {

        if ($request->isMethod('POST')) {
            
            $avatarMaxFileSize = _const('AVATAR_MAX');
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

            $user             = user();
            $pathToAvatar     = config('front.avatar_path');
            $newFileUpload    = upload($request, $pathToAvatar, [
                $user->avatar_big,
                $user->avatar_medium,
                $user->avatar_small
            ], [
                'prefix' => 'avatar_',
                'resize' => [
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

            $user->avatar_big    = $newFileUpload['big'];
            $user->avatar_medium = $newFileUpload['medium'];
            $user->avatar_small  = $newFileUpload['small'];

            try {
                $user->save();
            } catch (Exception $ex) {
                return ajax_upload_response([
                    'status'   => _const('AJAX_OK'),
                    'messages' => _t('opp')
                ], 500);
            }

            return ajax_upload_response([
                'status'   => _const('AJAX_OK'),
                'messages' => _t('saved_info'),
                'data'     => [
                    'big'     => asset($pathToAvatar . $newFileUpload['big']),
                    'medium'  => asset($pathToAvatar . $newFileUpload['medium']),
                    'small'   => asset($pathToAvatar . $newFileUpload['small']),
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
        $user       = user();
        $store      = store();
        if ($user->has_store) {
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
            
            $coverMaxFileSize = _const('COVER_MAX');
            $rules            = [
                '__file' => 'required|image|mimes:jpg,png,jpeg,gif|max:' . $coverMaxFileSize
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
            
            $store         = store();
            $pathToCover   = config('front.cover_path');
            $newFileUpload = upload($request, $pathToCover, [
                $store->cover_big,
                $store->cover_medium,
                $store->cover_small,
            ], [
                'prefix' => 'cover_',
                'resize' => [
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
                ]
            ]);
            
            $store->cover_big    = $newFileUpload['big'];
            $store->cover_medium = $newFileUpload['medium'];
            $store->cover_small  = $newFileUpload['small'];
            
            try {
                $store->update();
            } catch (Exception $ex) {
                return ajax_upload_response([
                    'status'   => _const('AJAX_OK'),
                    'messages' => _t('opp')
                ], 500);
            }

            return ajax_upload_response([
                'status'   => _const('AJAX_OK'),
                'messages' => _t('saved_info'),
                'data'     => [
                    'big'    => asset($pathToCover . $newFileUpload['big']),
                    'medium' => asset($pathToCover . $newFileUpload['medium']),
                    'small'  => asset($pathToCover . $newFileUpload['small']),
                ]
            ]);
        }
    }
}
