<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Asset
 *
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string $purchase_date
 * @property float $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Asset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Asset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Asset query()
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset wherePurchaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereUserId($value)
 * @mixin \Eloquent
 * @property int $farm_id
 * @property string $location
 * @property-read \App\Models\Farm $farm
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereFarmId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereLocation($value)
 */
class Asset extends Model
{
    use HasFactory, Searchable;

    protected $guarded = [];

    protected $casts = [
        'purchase_date' => 'date:Y-m-d'
    ];

    public $searchableColumns = [
        'type',
        'purchase_date',
        'price',
        'location',
        'farm_id'
    ];

    public function getImageAttribute($value)
    {
        return asset(Storage::url($value));
    }

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}
