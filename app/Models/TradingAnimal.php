<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TradingAnimal
 *
 * @property int $id
 * @property string $image
 * @property string $type
 * @property float $price
 * @property string $dob
 * @property string $location
 * @property string $dated
 * @property string $phone
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|TradingAnimal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TradingAnimal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TradingAnimal query()
 * @method static \Illuminate\Database\Eloquent\Builder|TradingAnimal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TradingAnimal whereDated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TradingAnimal whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TradingAnimal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TradingAnimal whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TradingAnimal whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TradingAnimal wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TradingAnimal wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TradingAnimal whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TradingAnimal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TradingAnimal whereUserId($value)
 * @mixin \Eloquent
 */
class TradingAnimal extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'dob' => 'date',
        'dated' => 'date'
    ];

    public function getImageAttribute($value)
    {
        return asset($value);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
