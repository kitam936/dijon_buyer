<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Role extends Model
{
    protected $fillable = [
        'id',
        'role_name',
        'role_info',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
