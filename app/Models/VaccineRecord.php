<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\VaccineRecord
 *
 * @property int $id
 * @property int $user_id
 * @property int $animal_id
 * @property string $name
 * @property string $reason
 * @property string $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|VaccineRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VaccineRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VaccineRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|VaccineRecord whereAnimalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VaccineRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VaccineRecord whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VaccineRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VaccineRecord whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VaccineRecord whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VaccineRecord whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VaccineRecord whereUserId($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Animal $animal
 * @property-read \App\Models\User $user
 */
class VaccineRecord extends Model
{
    use HasFactory, Searchable;

    protected $guarded = [];

    public $searchableColumns = [
        'date',
        'reason',
        'name',
        'animal_id',
        'user_id'
    ];

    public function getCertificateImageAttribute($value)
    {
        return asset(Storage::url($value));
    }

    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
