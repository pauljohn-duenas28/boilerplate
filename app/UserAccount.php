<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Hash;

class UserAccount extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'email_address', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // protected $appends = [
    //     'fullname', 'role'
    // ];
}
