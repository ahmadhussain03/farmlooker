<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    /**
     * Get the parent expensable model (animal or order feed).
     */
    public function incomeable()
    {
        return $this->morphTo();
    }
}
