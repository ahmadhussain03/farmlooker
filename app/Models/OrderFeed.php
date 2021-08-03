<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrderFeed
 *
 * @property int $id
 * @property string $name
 * @property string $phone_no
 * @property string $address
 * @property string $description
 * @property float $quantity
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FeedType[] $feedTypes
 * @property-read int|null $feed_types_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFeed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFeed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFeed query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFeed whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFeed whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFeed whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFeed whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFeed whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFeed wherePhoneNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFeed whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFeed whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFeed whereUserId($value)
 * @mixin \Eloquent
 */
class OrderFeed extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function feedTypes()
    {
        return $this->hasMany(FeedType::class);
    }
}
