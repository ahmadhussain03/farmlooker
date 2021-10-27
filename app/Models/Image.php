<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $guarded = [];

     /**
     * Get the parent expensable model (animal or order feed).
     */
    public function imageable()
    {
        return $this->morphTo();
    }


    public function getImageAttribute($value)
    {
        return asset($value);
    }
}
