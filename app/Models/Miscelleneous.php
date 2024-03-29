<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Miscelleneous extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get all of the post's comments.
     */
    public function expense()
    {
        return $this->morphOne(Expense::class, 'expenseable');
    }

    /**
     * model life cycle event listeners
     */
    public static function boot()
    {
        parent::boot();

        static::saved(function ($instance){
            if($instance->expense()->exists()){
                $instance->expense()->delete();
            }
            $expense = new Expense();
            $expense->amount = $instance->amount;
            $expense->farm_id = $instance->farm_id;
            $expense->dated = $instance->dated;
            $instance->expense()->save($expense);
        });
    }
}
