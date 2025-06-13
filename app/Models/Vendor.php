<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'id',
        'vendor_name',
        'vendor_info',
    ];
}
