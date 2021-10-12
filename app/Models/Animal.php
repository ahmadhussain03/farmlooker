<?php

namespace App\Models;

use App\Models\Animal as ModelsAnimal;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Animal
 *
 * @property int $id
 * @property string $animal_id
 * @property string $type
 * @property string $breed
 * @property string $add_as
 * @property string $sex
 * @property \Illuminate\Support\Carbon $dob
 * @property \Illuminate\Support\Carbon|null $purchase_date
 * @property string $location
 * @property string $disease
 * @property float|null $price
 * @property string|null $previous_owner
 * @property int|null $male_breeder_id
 * @property int|null $female_breeder_id
 * @property int $farm_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Farm $farm
 * @property-read Animal|null $femaleParent
 * @property-read Animal|null $femaleParentTree
 * @property-read Animal|null $maleParent
 * @property-read Animal|null $maleParentTree
 * @method static \Illuminate\Database\Eloquent\Builder|Animal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Animal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Animal query()
 * @method static \Illuminate\Database\Eloquent\Builder|Animal whereAddAs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Animal whereAnimalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Animal whereBreed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Animal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Animal whereDisease($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Animal whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Animal whereFarmId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Animal whereFemaleBreederId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Animal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Animal whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Animal whereMaleBreederId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Animal wherePreviousOwner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Animal wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Animal wherePurchaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Animal whereSex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Animal whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Animal whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Animal extends Model
{
    use HasFactory, Searchable;

    protected $guarded = [];

    protected $casts = [
      'dob' => 'date:Y-m-d',
      'purchase_date' => 'date:Y-m-d'
    ];

    public $searchableColumns = [
        'animal_id',
        'type',
        'breed',
        'add_as',
        'sex',
        'dob',
        'purchase_date',
        'location',
        'disease',
        'price',
        'previous_owner',
        'male_breeder_id',
        'female_breeder_id',
        'farm_id'
    ];

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function breed()
    {
        return $this->belongsTo(Breed::class);
    }

     /**
     * Get all of the post's comments.
     */
    public function expense()
    {
        return $this->morphOne(Expense::class, 'expenseable');
    }

    public function farm(): BelongsTo
    {
        return $this->belongsTo(Farm::class);
    }

    public function maleParent(): BelongsTo
    {
        return $this->belongsTo(Animal::class, 'male_breeder_id', 'id');
    }

    public function maleParentTree(): BelongsTo
    {
        return $this->belongsTo(Animal::class, 'male_breeder_id', 'id')->with(['maleParentTree', 'femaleParentTree']);
    }

    public function femaleParent(): BelongsTo
    {
        return $this->belongsTo(Animal::class, 'female_breeder_id', 'id');
    }

    public function femaleParentTree(): BelongsTo
    {
        return $this->belongsTo(Animal::class, 'female_breeder_id', 'id')->with(['maleParentTree', 'femaleParentTree']);
    }

    /**
     * model life cycle event listeners
     */
    public static function boot()
    {
        parent::boot();

        static::saved(function ($instance){
            if($instance->add_as == 'purchased'){
                if($instance->expense()->exists()){
                    $instance->expense()->delete();
                }
                $expense = new Expense();
                $expense->amount = $instance->price;
                $expense->farm_id = $instance->farm_id;
                $instance->expense()->save($expense);
            }
        });

        static::saving(function($instance){
            if(!$instance->auid){
                $instance->auid = $instance->farm->name;

                $instance->auid .= '_' . $instance->animal_id;
                $instance->auid .= "_" . $instance->dob->format('Y_m_d');
                $instance->auid .= "_" . $instance->farm->city_id;
                $instance->auid .= $instance->sex == 'male' ? '_M' : '_F';
                $instance->auid .= "_" . self::getRandomString();
            }
        });
    }

    public static function getRandomString($length = 7)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*(){}[]?<>~';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
