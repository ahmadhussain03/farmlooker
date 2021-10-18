<?php

namespace App\Models;

use App\Notifications\EmailVerification;
use App\Traits\Searchable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Storage;
use Str;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $phone_no
 * @property string|null $experience
 * @property string|null $device_token
 * @property int|null $parent_id
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $user_type
 * @property-read \App\Models\UserSubscription|null $activeSubscription
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Animal[] $animals
 * @property-read int|null $animals_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Farm[] $farms
 * @property-read int|null $farms_count
 * @property-read mixed $full_name
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeviceToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhoneNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUserType($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens, HasRelationships, Searchable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_no',
        'experience',
        'image',
        'parent_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'pivot',
        'device_token',
        'parent_id',
        'user_type'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['fullName'];

    public $searchableColumns = [
        'first_name',
        'last_name',
        'email',
        'location',
        'phone_no',
        'experience',
        'parent_id'
    ];

     /**
     * Specifies the user's FCM token
     *
     * @return string|array
     */
    public function routeNotificationForFcm()
    {
        return $this->device_token;
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getImageAttribute($value)
    {
        return asset(Storage::url($value));
    }

    public function sendEmailVerificationNotification()
    {
        $verificationCode = Str::random(6);

        if($code = $this->verificationCode()->first()){
            $code->delete();
        }

        $this->verificationCode()->create(['code' => $verificationCode]);

        $this->notify(new EmailVerification($verificationCode));
    }

    public function verificationCode()
    {
        return $this->hasOne(UserEmailVerificationCode::class);
    }

    public function animals()
    {
        return $this->hasManyDeep(Animal::class, ['farm_user', Farm::class]);
    }

    public function expenses()
    {
        return $this->hasManyDeep(Expense::class, ['farm_user', Farm::class]);
    }

    public function incomes()
    {
        return $this->hasManyDeep(Income::class, ['farm_user', Farm::class]);
    }

    public function workers()
    {
        return $this->hasManyDeep(Worker::class, ['farm_user', Farm::class]);
    }

    public function vaccineRecords()
    {
        return $this->hasManyDeep(VaccineRecord::class, ['farm_user', Farm::class, Animal::class]);
    }

    public function diseaseAlerts()
    {
        return $this->hasManyDeep(DiseaseAlert::class, ['farm_user', Farm::class, Animal::class]);
    }

    public function assets()
    {
        return $this->hasManyDeep(Asset::class, ['farm_user', Farm::class]);
    }

    public function tradingAnimals()
    {
        return $this->hasMany(TradingAnimal::class);
    }

    public function rentalEquipments()
    {
        return $this->hasMany(RentalEquipment::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(UserSubscription::class, 'user_id', 'id')->where('status', 'SUCCESSFULL');
    }

    public function farms()
    {
        return $this->belongsToMany(Farm::class);
    }

    public function farm()
    {
        return $this->belongsToMany(Farm::class)->first();
    }
}
