<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Farm
 *
 * @property int $id
 * @property string $location
 * @property string $area_of_hector
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $admin
 * @property-read int|null $admin_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Animal[] $animals
 * @property-read int|null $animals_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $members
 * @property-read int|null $members_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $moderators
 * @property-read int|null $moderators_count
 * @method static \Illuminate\Database\Eloquent\Builder|Farm newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Farm newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Farm query()
 * @method static \Illuminate\Database\Eloquent\Builder|Farm whereAreaOfHector($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farm whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farm whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farm whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Farm whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Worker[] $workers
 * @property-read int|null $workers_count
 */
class Farm extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = ['pivot'];

    protected $casts = [
        'geometry' => 'json'
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function herds()
    {
        return $this->hasMany(Herd::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class);
    }

    public function admin()
    {
        return $this->belongsToMany(User::class)->where('user_type', 'admin');
    }

    public function moderators()
    {
        return $this->belongsToMany(User::class)->where('user_type', 'moderator');
    }

    public function animals()
    {
        return $this->hasMany(Animal::class);
    }

    public function workers()
    {
        return $this->hasMany(Worker::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function farmInfos()
    {
        return $this->hasMany(FarmInfo::class);
    }
}
