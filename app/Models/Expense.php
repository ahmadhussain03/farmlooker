<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $guarded = [];

     /**
     * Get the parent commentable model (post or video).
     */
    public function expenseable()
    {
        return $this->morphTo();
    }
}
