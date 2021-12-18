<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
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
 */
	class Animal extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Asset
 *
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string $purchase_date
 * @property float $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Asset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Asset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Asset query()
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset wherePurchaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Asset whereUserId($value)
 */
	class Asset extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\DiseaseAlert
 *
 * @property int $id
 * @property string $description
 * @property array $symptoms
 * @property int $user_id
 * @property int $animal_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Animal $animal
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|DiseaseAlert newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DiseaseAlert newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DiseaseAlert query()
 * @method static \Illuminate\Database\Eloquent\Builder|DiseaseAlert whereAnimalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiseaseAlert whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiseaseAlert whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiseaseAlert whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiseaseAlert whereSymptoms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiseaseAlert whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiseaseAlert whereUserId($value)
 */
	class DiseaseAlert extends \Eloquent {}
}

namespace App\Models{
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
 */
	class Farm extends \Eloquent {}
}

namespace App\Models{
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
 */
	class FeedType extends \Eloquent {}
}

namespace App\Models{
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
 */
	class OrderFeed extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\RentalEquipment
 *
 * @property int $id
 * @property string $image
 * @property string $name
 * @property string $model
 * @property float $rent
 * @property string $location
 * @property string $dated
 * @property string $phone
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|RentalEquipment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RentalEquipment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RentalEquipment query()
 * @method static \Illuminate\Database\Eloquent\Builder|RentalEquipment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RentalEquipment whereDated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RentalEquipment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RentalEquipment whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RentalEquipment whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RentalEquipment whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RentalEquipment whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RentalEquipment wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RentalEquipment whereRent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RentalEquipment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RentalEquipment whereUserId($value)
 */
	class RentalEquipment extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Subscription
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property float $amount
 * @property int $frequency
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription query()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereUpdatedAt($value)
 */
	class Subscription extends \Eloquent {}
}

namespace App\Models{
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
 */
	class TradingAnimal extends \Eloquent {}
}

namespace App\Models{
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
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserSubscription
 *
 * @property int $id
 * @property int $user_id
 * @property int $subscription_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSubscription query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSubscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSubscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSubscription whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSubscription whereSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSubscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSubscription whereUserId($value)
 */
	class UserSubscription extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\VaccineRecord
 *
 * @property int $id
 * @property int $user_id
 * @property int $animal_id
 * @property string $name
 * @property string $reason
 * @property string $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|VaccineRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VaccineRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VaccineRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|VaccineRecord whereAnimalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VaccineRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VaccineRecord whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VaccineRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VaccineRecord whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VaccineRecord whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VaccineRecord whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VaccineRecord whereUserId($value)
 */
	class VaccineRecord extends \Eloquent {}
}

namespace App\Models{
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
 */
	class Worker extends \Eloquent {}
}

