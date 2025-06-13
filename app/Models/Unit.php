<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Hinban;

class Unit extends Model
{
    protected $fillable = [
        'id',
        'season_id',
        'season_name',

    ];

    public function hinbans()
    {
        return $this->hasMany(Hinban::class);
    }

}
