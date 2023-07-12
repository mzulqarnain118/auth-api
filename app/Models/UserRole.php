<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;

    protected $table = 'bsc_user_role';

    protected $fillable = [
        'bsc_ur_name',
        'bsc_ur_color',
        'bsc_ur_status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
