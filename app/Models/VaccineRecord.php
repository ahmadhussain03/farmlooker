<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    use HasFactory;

    protected $guarded = [];

    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
