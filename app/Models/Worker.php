<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Worker
 *
 * @property int $id
 * @property string $name
 * @property string $phone_no
 * @property string $address
 * @property float $pay
 * @property string $location
 * @property string $joining_date
 * @property string $duty
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Worker newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Worker newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Worker query()
 * @method static \Illuminate\Database\Eloquent\Builder|Worker whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Worker whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Worker whereDuty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Worker whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Worker whereJoiningDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Worker whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Worker whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Worker wherePay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Worker wherePhoneNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Worker whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Worker whereUserId($value)
 * @mixin \Eloquent
 */
class Worker extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'joining_date' => 'date'
    ];

    public function farm(): BelongsTo
    {
        return $this->belongsTo(Farm::class);
    }
}
