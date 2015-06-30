<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
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
            'user_name.required'   => 'User name is required.',
            'user_name.alpha_dash' => 'Only allow a-z, 0-9 and underscore.',
            'user_name.min'        => 'User name is too short (6).',
            'user_name.max'        => 'User name is too long (32).',
            'user_name.unique'     => 'User name has been used.',
            'email.required'       => 'Email is required.',
            'email.email'          => 'Email is wrong format.',
            'email.max'            => 'Email is too long (128).',
            'email.unique'         => 'Email has been used.',
            'password.required'    => 'Password is required.',
            'password.min'         => 'Password is too short (6).',
            'password.max'         => 'Password is too long (60).',
            'first_name.max'       => 'First name is too long (16).',
            'last_name.max'        => 'Last name is too long (32).',
        ];

        return $messages;
    }
}
