<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedType extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function orderFeed()
    {
        return $this->belongsTo(OrderFeed::class);
    }
}
