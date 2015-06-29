<?php

/**
 * Handling:
 * + Login
 * + Register
 * + Forgot password
 *
 * @author vuongabc92@gmail.com
 */

namespace King\Frontend\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;

class AuthController extends FrontController
{

    /**
     * Login
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return response
     */
    public function authenticate(Request $request)
    {

        if ($request->isMethod('POST')) {

            $rules = [
                'email'    => 'required|email|max:128',
                'password' => 'required|max:60',
            ];

            $messages = [
                'email.required'    => _t('auth_email_req'),
                'email.email'       => _t('auth_email_email'),
                'email.max'         => _t('auth_email_max'),
                'password.required' => _t('auth_pass_req'),
                'password.max'      => _t('auth_pass_max'),
            ];

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->passes()) {
                $credentials = [
                    'email'    => $request->get('email'),
                    'password' => $request->get('password'),
                ];

                if (auth()->attempt($credentials, $request->has('remember_me'))) {
                    return redirect()->intended(route('front_home'));
                }

                $validator->errors()->add('email', _t('login_fails'));
            }

            return back()->withInput()->withErrors($validator, 'auth');
        }

        return view('frontend::auth.login');
    }

    public function register(Request $request)
    {

        if ($request->isMethod('POST')) {
            $rules = [
                'email'     => 'required|max:128|email|unique:users,email',
                'user_name' => 'required|min:6|max:32|unique:users,user_name',
                'password'  => 'required|min:6|max:60'
            ];

            $messages = [
                'email.required'     => _t('auth_email_req'),
                'email.email'        => _t('auth_email_email'),
                'email.max'          => _t('auth_email_max'),
                'email.unique'       => _t('auth_email_uni'),
                'password.required'  => _t('auth_pass_req'),
                'password.min'       => _t('auth_pass_min'),
                'password.max'       => _t('auth_pass_max'),
                'user_name.required' => _t('auth_uname_req'),
                'user_name.min'      => _t('auth_uname_min'),
                'user_name.max'      => _t('auth_uname_max'),
                'user_name.unique'   => _t('auth_uname_uni'),
            ];

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return back()->withInput()->withErrors($validator, 'auth');
            }

            $user = $this->bind(new User(), $request->all());
            try {
                $user->save();
            } catch (Exception $ex) {
                throw new Exception(_t('opp'));
            }

            $credentials = [
                'email'    => $request->get('email'),
                'password' => $request->get('password'),
            ];
            auth()->attempt($credentials);

            return redirect(route('front_home'));
        }

        return view('frontend::auth.register');
    }

}
