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

    protected $_user;

    public function __construct(User $user)
    {
        $this->_user = $user;
    }


    /**
     * Login
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return response
     */
    public function authenticate(Request $request) {

        if ($request->isMethod('POST')) {

            $rules = remove_rules($this->_user->getRules(), [
                'user_name',
                'first_name',
                'last_name',
                'password.min:6',
                'email.unique:users,email',
            ]);
            $messages  = $this->_user->getMessages();
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

    /**
     * User registering
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return response
     *
     * @throws Exception
     */
    public function register(Request $request) {
        
        if ($request->isMethod('POST')) {

            $rules     = remove_rules($this->_user->getRules(), ['first_name', 'last_name']);
            $messages  = $this->_user->getMessages();
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return back()->withInput()->withErrors($validator, 'auth');
            }

            $user = $this->bind($this->_user, $request->all());
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

    /**
     * Logout
     *
     * @return response
     */
    public function logout() {

        auth()->logout();

        return redirect(route('front_home'));
    }
}
