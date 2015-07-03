<?php

/**
 * FrontendController
 *
 * @author vuongabc92@gmail.com
 */

namespace King\Frontend\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Hash;
use File;

class SettingController extends FrontController
{

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

        /**
     * Display account setting page
     *
     * @return response
     */
    public function index()
    {
        return view('frontend::setting.account', ['user' => auth()->user()]);
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
                    'status'   => AJAX_ERROR,
                    'messages' => $validator->messages()
                ]);
            }

            $password    = $request->get('password');
            $newPass     = $request->get('new_password');
            $hashingPass = auth()->user()->password;

            /** Check confirm password */
            if (Hash::check($password, $hashingPass)) {

                auth()->user()->password = bcrypt($newPass);

                try {
                    auth()->user()->save();
                } catch (Exception $ex) {
                    return ajax_response([
                        'status'   => AJAX_ERROR,
                        'messages' => _t('opp')
                    ]);
                }

                return ajax_response([
                    'status'   => AJAX_OK,
                    'messages' => _t('saved_pass')
                ]);
            }

            $validator->errors()->add('password', _t('pass_wrong'));

            return ajax_response([
                'status'   => AJAX_ERROR,
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

            $user            = auth()->user();
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
                    'status' => AJAX_ERROR,
                    'messages' => $validator->messages()
                ]);
            }

            //Check does password from input match to password in DB.
            if ($checkPass) {
                if ( ! Hash::check($password, $hashingPass)) {
                    $validator->errors()->add('password', _t('pass_incorrect'));
                    return ajax_response([
                        'status' => AJAX_ERROR,
                        'messages' => $validator->messages()
                    ]);
                }
            }

            $user->user_name  = $username;
            $user->email      = $email;
            $user->first_name = $fname;
            $user->last_name  = $lname;
            try {
                $user->save();
            } catch (Exception $ex) {
                return ajax_response([
                    'status'   => AJAX_ERROR,
                    'messages' => _t('opp')
                ]);
            }

            return ajax_response([
                'status'   => AJAX_OK,
                'messages' => _t('saved_info')
            ]);
        }
    }

    public function ajaxChangeAvatar(Request $request) {

        if ($request->isMethod('POST')) {
            $rules = [
                'avatar' => 'required|image|mimes:jpg,png,jpeg,gif|max:10000'
            ];

            $messages = [
                'avatar.required' => 'No image was chose.',
                'avatar.image'    => 'Avatar is not an image.',
                'avatar.mimes'    => 'Image must be in (jpg, png, jpeg, gif).',
                'avatar.max'      => 'Image size is too big (10M).',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return ajax_response([
                    'status'   => AJAX_ERROR,
                    'messages' => $validator->messages()
                ]);
            }
            die('pass rules');
            $user = auth()->user();

            /** Remove current user avatar if exist. */
            if ($user->avatar !== null) {
                $currentAvatarPath = config('front.avatar_path') . $user->avatar;
                if ( ! is_dir($currentAvatarPath) && file_exists($currentAvatarPath)) {
//                    try {
//                        //File::delete($currentAvatarPath)
//                    } catch (Exception $ex) {
//
//                    }
                }
            }
        }
    }

}
