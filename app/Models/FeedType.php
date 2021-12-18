<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FeedType
 *
 * @property int $id
 * @property string $feed
 * @property int $order_feed_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\OrderFeed $orderFeed
 * @method static \Illuminate\Database\Eloquent\Builder|FeedType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedType query()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedType whereFeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedType whereOrderFeedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FeedType extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function orderFeed()
    {
        return $this->belongsTo(OrderFeed::class);
    }
}
