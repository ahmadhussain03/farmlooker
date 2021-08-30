<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RentalEquipment
 *
 * @property int $id
 * @property string $image
 * @property string $name
 * @property string $model
 * @property float $rent
 * @property string $location
 * @property string $dated
 * @property string $phone
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|RentalEquipment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RentalEquipment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RentalEquipment query()
 * @method static \Illuminate\Database\Eloquent\Builder|RentalEquipment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RentalEquipment whereDated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RentalEquipment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RentalEquipment whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RentalEquipment whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RentalEquipment whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RentalEquipment whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RentalEquipment wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RentalEquipment whereRent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RentalEquipment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RentalEquipment whereUserId($value)
 * @mixin \Eloquent
 */
class RentalEquipment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'dated' => 'date'
    ];

    public function getImageAttribute($value)
    {
        return asset($value);
    }
}
