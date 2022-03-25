<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmInfo extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = [
        'farm_id'
    ];

    protected $casts = [
        'msavi' => 'json',
        'ndre' => 'json',
        'ndvi' => 'json',
        'recl' => 'json',
    ];
}
