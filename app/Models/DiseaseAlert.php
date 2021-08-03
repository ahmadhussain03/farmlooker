<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DiseaseAlert
 *
 * @property int $id
 * @property string $description
 * @property array $symptoms
 * @property int $user_id
 * @property int $animal_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Animal $animal
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|DiseaseAlert newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DiseaseAlert newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DiseaseAlert query()
 * @method static \Illuminate\Database\Eloquent\Builder|DiseaseAlert whereAnimalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiseaseAlert whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiseaseAlert whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiseaseAlert whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiseaseAlert whereSymptoms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiseaseAlert whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiseaseAlert whereUserId($value)
 * @mixin \Eloquent
 */
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
