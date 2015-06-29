<?php

/**
 * FrontendController
 *
 * @author vuongabc92@gmail.com
 */

namespace King\Frontend\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Hash;

class SettingController extends FrontController
{

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
                'password.required'     => _t('auth_pass_req'),
                'password_new.required' => _t('auth_pass_req'),
                'password_new.min'      => _t('auth_pass_min'),
                'password_new.max'      => _t('auth_pass_max'),
            ];

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                ajax_response([
                    'status'   => AJAX_ERROR,
                    'messages' => $validator->messages()
                ]);
            }

            $password    = $request->get('password');
            $newPass     = $request->get('new_password');
            $hashingPass = auth()->user()->password;

            /*
             * Check whether the current password that user input is match
             * to the password that has been hashed and save in DB
             */
            if (Hash::check($password, $hashingPass)) {
                auth()->user()->password = bcrypt($newPass);
                try {
                    auth()->user()->save();
                } catch (Exception $ex) {
                    ajax_response([
                        'status'   => AJAX_ERROR,
                        'messages' => _t('opp')
                    ]);
                }

                ajax_response([
                    'status'   => AJAX_OK,
                    'messages' => _t('saved_pass')
                ]);
            }

            ajax_response([
                'status'   => AJAX_ERROR,
                'messages' => _t('curr_pass_wrong')
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

            $rules = [
                'user_name'  => 'required|min:6|max:32|unique:users,user_name',
                'email'      => 'required|max:128|email|unique:users,email',
                'first_name' => 'max:16',
                'last_name'  => 'max:32',
            ];

            $messages = [
                'email.required'     => _t('auth_email_req'),
                'email.email'        => _t('auth_email_email'),
                'email.max'          => _t('auth_email_max'),
                'email.unique'       => _t('auth_email_uni'),
                'user_name.required' => _t('auth_uname_req'),
                'user_name.min'      => _t('auth_uname_min'),
                'user_name.max'      => _t('auth_uname_max'),
                'user_name.unique'   => _t('auth_uname_uni'),
                'first_name.max'     => 'First name is too long.',
                'last_name.max'      => 'Last name is too long.',
            ];

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
                $rules['user_name'] = 'required|min:6|max:32';
            } else {
                $checkPass = true;
            }

            //Email is same with username.
            if (str_equal($currentEmail, $email)) {
                $rules['email'] = 'required|max:128|email';
            } else {
                $checkPass = true;
            }

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                ajax_response([
                    'status' => AJAX_ERROR,
                    'messages' => $validator->messages()
                ]);
            }

            //Check does password from input match to password in DB.
            if ($checkPass) {
                if ( ! Hash::check($password, $hashingPass)) {
                    ajax_response([
                        'status' => AJAX_ERROR,
                        'messages' => 'Password is incorrect.'
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
                ajax_response([
                    'status'   => AJAX_ERROR,
                    'messages' => _t('opp')
                ]);
            }

            ajax_response([
                'status'   => AJAX_OK,
                'messages' => 'Saved info.'
            ]);
        }
    }

    public function ajaxChangeAvatar(Request $request) {

        $rules = [
            'avatar' => 'required|image|mimes:jpg,png,jpeg,gif,size:10000'
        ];

        $messages = [
            'avatar.required' => 'Avatar is empty.',
            'avatar.image'    => 'Avatar must be image.',
            'avatar.mimes'    => 'Avatar must be in (jpg, png, jpeg, gif).',
            'avatar.size'     => 'Avatar size must be lower than 10 megabyte.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            ajax_response([
                'status'   => AJAX_ERROR,
                'messages' => $validator->messages()
            ]);
        }

        $user = auth()->user();

        /**
         * If current user avatar exists then remove it
         */
        if ($user->avatar !== null) {
            $currentAvatar = '.' . $user->avatar;
            if ( ! is_dir($currentAvatar) && file_exists($currentAvatar)) {
                
            }
        }
    }

//    git config core.filemode false
//    git config --global core.autocrlf true
}
