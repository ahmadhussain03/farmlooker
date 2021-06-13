<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiseaseAlert extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'symptoms' => 'array'
    ];

    public function getSymptomsAttribute($value)
    {
        return json_decode(json_decode($value));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function animal()
    {

        return $this->belongsTo(Animal::class);
    }
}
