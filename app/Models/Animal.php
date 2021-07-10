<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Animal extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
      'dob' => 'date',
      'purchase_date' => 'date'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function maleParent(): BelongsTo
    {
        return $this->belongsTo(Animal::class, 'male_breeder_id', 'id');
    }

    public function maleParentTree(): BelongsTo
    {
        return $this->belongsTo(Animal::class, 'male_breeder_id', 'id')->with(['maleParentTree', 'femaleParentTree']);
    }

    public function femaleParent(): BelongsTo
    {
        return $this->belongsTo(Animal::class, 'female_breeder_id', 'id');
    }

    public function femaleParentTree(): BelongsTo
    {
        return $this->belongsTo(Animal::class, 'female_breeder_id', 'id')->with(['maleParentTree', 'femaleParentTree']);
    }
}
