<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalSold extends Model
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
            $income->farm_id = $instance->previous_farm;
            $income->dated = $instance->dated;
            $instance->income()->save($income);
        });
    }
}
