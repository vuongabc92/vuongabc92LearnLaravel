<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Base implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Get store
     * 
     * @return App\Models\Store
     */
    public function store() {
        return $this->hasOne('App\Models\Store');
    }
    
    /**
     * Get user rules
     *
     * @return array
     */
    public function getRules()
    {
        $rules = [
            'user_name'  => 'required|alpha_dash|min:6|max:32|unique:users,user_name',
            'email'      => 'required|email|max:128|unique:users,email',
            'password'   => 'required|min:6|max:60',
            'first_name' => 'max:16',
            'last_name'  => 'max:32',
        ];

        return $rules;
    }

    public function getMessages()
    {
        $messages = [
            'user_name.required'   => _t('user_uname_req'),
            'user_name.alpha_dash' => _t('user_uname_alpha'),
            'user_name.min'        => _t('user_uname_min'),
            'user_name.max'        => _t('user_uname_max'),
            'user_name.unique'     => _t('user_uname_uni'),
            'email.required'       => _t('user_email_req'),
            'email.email'          => _t('user_email_email'),
            'email.max'            => _t('user_email_max'),
            'email.unique'         => _t('user_email_uni'),
            'password.required'    => _t('user_pass_req'),
            'password.min'         => _t('user_pass_min'),
            'password.max'         => _t('user_pass_max'),
            'first_name.max'       => _t('user_fname_max'),
            'last_name.max'        => _t('user_lname_max'),
        ];

        return $messages;
    }
}
