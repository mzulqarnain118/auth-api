<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'bsc_user';

    protected $fillable = [
        'name',
        'email',
        'password',
        'bsc_u_ur_id',
        'bsc_u_username',
        'bsc_u_password',
        'bsc_u_auth_key',
        'bsc_u_is_verified',
        'bsc_u_added_by',
        'bsc_u_added_on',
        'bsc_u_updated_by',
        'bsc_u_updated_on',
        'bsc_u_status',
        'minor_email',
        'parent_email',
        'parent_name',
        'parent_phone',
        'ITS',
        'DOB',
        'gender',
        'jamaat',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
