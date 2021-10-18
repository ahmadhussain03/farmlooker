<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherIncome extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get all of the post's comments.
     */
    public function income()
    {
        return $this->morphOne(Income::class, 'incomeable');
    }

    /**
     * model life cycle event listeners
     */
    public static function boot()
    {
        parent::boot();

        static::saved(function ($instance){
            if($instance->income()->exists()){
                $instance->income()->delete();
            }
            $income = new Income();
            $income->amount = $instance->amount;
            $income->farm_id = $instance->farm_id;
            $instance->income()->save($income);
        });
    }
}
