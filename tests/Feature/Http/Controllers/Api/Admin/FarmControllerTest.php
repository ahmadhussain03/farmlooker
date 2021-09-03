<?php

namespace Tests\Feature\Http\Controllers\Api\Admin;

use App\Models\Farm;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FarmControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withHeaders([
            'Accept' => 'application/json'
        ]);
    }

    public function getFarmData($data = [])
    {
        return array_merge(Farm::factory()->make()->toArray(), $data);
    }

    public function test_user_cannot_create_farm_without_authentication()
    {
        $response = $this->postJson("api/admin/farm", []);

        $response->assertStatus(401);
        $response->assertJson(function(AssertableJson $json){
            $json
                ->has('message')
                ->where('message', 'Unauthenticated.')
                ->where('code', 401)
                ->has('data');
        });
    }


    public function test_user_cannot_create_farm_unless_admin()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson("api/admin/farm", []);

        $response->assertStatus(403);
        $response->assertJson(function(AssertableJson $json){
            $json
                ->has('message')
                ->where('message', 'Forbidden.')
                ->where('code', 403)
                ->has('data');
        });
    }

    public function test_farm_cannot_created_without_location()
    {
        // $this->withoutExceptionHandling();
        Sanctum::actingAs(User::factory()->create(['user_type' => 'admin']));

        $farm = $this->getFarmData(['location' => ""]);

        $response = $this->postJson('/api/admin/farm', $farm);

        $response->assertStatus(422);
        $response->assertJson(function(AssertableJson $json){
            $json
                ->has('data')
                ->has('data.location', 1)
                ->where('data.location.0', 'The location field is required.')
                ->has('message')
                ->where('message', 'The given data was invalid.')
                ->has('code')
                ->where('code', 422);
        });
    }

    public function test_farm_cannot_created_without_area()
    {
        // $this->withoutExceptionHandling();
        Sanctum::actingAs(User::factory()->create(['user_type' => 'admin']));

        $farm = $this->getFarmData(['area_of_hector' => ""]);

        $response = $this->postJson('/api/admin/farm', $farm);

        $response->assertStatus(422);
        $response->assertJson(function(AssertableJson $json){
            $json
                ->has('data')
                ->has('data.area_of_hector', 1)
                ->where('data.area_of_hector.0', 'The area of hector field is required.')
                ->has('message')
                ->where('message', 'The given data was invalid.')
                ->has('code')
                ->where('code', 422);
        });
    }

    public function test_user_can_create_farm()
    {
        $user = Sanctum::actingAs(User::factory()->create(['user_type' => 'admin']));

        $farm = $this->getFarmData();
        $response = $this->postJson("api/admin/farm", $farm);
        $response->assertStatus(200);
        $response->assertJson(function(AssertableJson $json) use ($farm){
            $json
                ->has('code')
                ->has('message')
                ->where('code', 200)
                ->has('data', function(AssertableJson $json) use ($farm){
                    $json
                        ->has('id')
                        ->where('location', $farm['location'])
                        ->where('area_of_hector', $farm['area_of_hector'])
                        ->has('created_at')
                        ->has('updated_at');
                });
        });

        $farmDB = Farm::first();

        $this->assertEquals($farmDB->location, $farm['location']);
        $this->assertEquals($farmDB->area_of_hector, $farm['area_of_hector']);

        $userFarms = $user->farms;

        $this->assertCount(1, $userFarms);

        $userFarm = $userFarms->first();

        $this->assertEquals($userFarm->location, $farm['location']);
        $this->assertEquals($userFarm->area_of_hector, $farm['area_of_hector']);
    }

    public function test_user_cannot_view_farms_without_authentication()
    {
        $response = $this->getJson("api/admin/farm");

        $response->assertStatus(401);
        $response->assertJson(function(AssertableJson $json){
            $json
                ->has('message')
                ->where('message', 'Unauthenticated.')
                ->where('code', 401)
                ->has('data');
        });
    }


    public function test_user_cannot_view_farms_unless_admin()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->getJson("api/admin/farm");

        $response->assertStatus(403);
        $response->assertJson(function(AssertableJson $json){
            $json
                ->has('message')
                ->where('message', 'Forbidden.')
                ->where('code', 403)
                ->has('data');
        });
    }


    public function test_user_view_farms()
    {
        /** @var App\Models\User $user  */
        $user = Sanctum::actingAs(User::factory()->hasAttached(Farm::factory()->count(2))->create(['user_type' => 'admin']));

        $response = $this->getJson("api/admin/farm");

        $response->assertStatus(200);
        $response->assertJson(function(AssertableJson $json) use ($user){
            $json
                ->has('message')
                ->where('message', null)
                ->where('code', 200)
                ->has('data', function(AssertableJson $json) use ($user){
                    $json
                        ->has('current_page')
                        ->where('current_page', 1)
                        ->has('first_page_url')
                        ->has('last_page_url')
                        ->has('from')
                        ->has('last_page')
                        ->has('next_page_url')
                        ->has('path')
                        ->has('per_page')
                        ->where('per_page', 10)
                        ->has('prev_page_url')
                        ->has('to')
                        ->has('total')
                        ->where('total', 2)
                        ->has('links', 3)
                        ->has('data', function(AssertableJson $json) use ($user){
                            $json
                                ->has(2)
                                ->first(function(AssertableJson $json) use ($user){
                                    $json
                                        ->has('id')
                                        ->has('location')
                                        ->has('area_of_hector')
                                        ->has('created_at')
                                        ->has('updated_at')
                                        ->where('id', $user->farms()->first()->id)
                                        ->where('location', $user->farms()->first()->location)
                                        ->where('area_of_hector', $user->farms()->first()->area_of_hector);
                                })->etc();
                        });
                });
        });

    }

    public function test_farm_can_update_without_any_data()
    {
        // $this->withoutExceptionHandling();
        /** @var App\Models\User $user  */
        $user = Sanctum::actingAs(User::factory()->hasAttached(Farm::factory()->count(2))->create(['user_type' => 'admin']));

        $farm = $user->farms()->first();

        $response = $this->putJson('/api/admin/farm/' . $farm->id, []);

        $response->assertStatus(200);
        $response->assertJson(function(AssertableJson $json) use ($farm){
            $json
                ->has('data', function(AssertableJson $json) use ($farm){
                    $json
                        ->where('id', $farm->id)
                        ->where('location', $farm->location)
                        ->where('area_of_hector', $farm->area_of_hector)
                        ->has('created_at')
                        ->has('updated_at');
                })
                ->has('message')
                ->has('code')
                ->where('code', 200);
        });
    }

    public function test_farm_cannot_update_without_location()
    {
        // $this->withoutExceptionHandling();
        /** @var App\Models\User $user  */
        $user = Sanctum::actingAs(User::factory()->hasAttached(Farm::factory()->count(2))->create(['user_type' => 'admin']));

        $farm = $user->farms()->first();

        $response = $this->putJson('/api/admin/farm/' . $farm->id, ['location' => '']);

        $response->assertStatus(422);
        $response->assertJson(function(AssertableJson $json){
            $json
                ->has('data')
                ->has('data.location', 1)
                ->where('data.location.0', 'The location must be a string.')
                ->has('message')
                ->where('message', 'The given data was invalid.')
                ->has('code')
                ->where('code', 422);
        });
    }

    public function test_farm_cannot_update_without_area()
    {
        // $this->withoutExceptionHandling();
        /** @var App\Models\User $user  */
        $user = Sanctum::actingAs(User::factory()->hasAttached(Farm::factory()->count(2))->create(['user_type' => 'admin']));

        $farm = $user->farms()->first();

        $response = $this->putJson('/api/admin/farm/' . $farm->id, ['area_of_hector' => '']);

        $response->assertStatus(422);
        $response->assertJson(function(AssertableJson $json){
            $json
                ->has('data')
                ->has('data.area_of_hector', 1)
                ->where('data.area_of_hector.0', 'The area of hector must be a number.')
                ->has('message')
                ->where('message', 'The given data was invalid.')
                ->has('code')
                ->where('code', 422);
        });
    }

    public function test_user_can_update_farm_location()
    {
        /** @var App\Models\User $user  */
        $user = Sanctum::actingAs(User::factory()->hasAttached(Farm::factory()->count(2))->create(['user_type' => 'admin']));

        $farm = $user->farms()->first();

        $updatedCity = $this->faker->city();
        $response = $this->putJson("api/admin/farm/" . $farm->id, ['location' => $updatedCity]);

        $response->assertOk();
        $response->assertJson(function(AssertableJson $json) use ($farm, $updatedCity){
            $json
                ->has('code')
                ->has('message')
                ->where('code', 200)
                ->has('data', function(AssertableJson $json) use ($farm, $updatedCity){
                    $json
                        ->has('id')
                        ->where('location', $updatedCity)
                        ->where('area_of_hector', $farm->area_of_hector)
                        ->has('created_at')
                        ->has('updated_at');
                });
        });
    }

    public function test_user_can_update_farm_area()
    {
        /** @var App\Models\User $user  */
        $user = Sanctum::actingAs(User::factory()->hasAttached(Farm::factory()->count(2))->create(['user_type' => 'admin']));

        $farm = $user->farms()->first();

        $updatedArea = $this->faker->numberBetween(100, 10000);
        $response = $this->putJson("api/admin/farm/" . $farm->id, ['area_of_hector' => $updatedArea]);

        $response->assertOk();
        $response->assertJson(function(AssertableJson $json) use ($farm, $updatedArea){
            $json
                ->has('code')
                ->has('message')
                ->where('code', 200)
                ->has('data', function(AssertableJson $json) use ($farm, $updatedArea){
                    $json
                        ->has('id')
                        ->where('location', $farm->location)
                        ->where('area_of_hector', $updatedArea)
                        ->has('created_at')
                        ->has('updated_at');
                });
        });
    }

    public function test_user_cannot_other_user_farm()
    {
        /** @var App\Models\User $user  */
        $userOne = User::factory()->hasAttached(Farm::factory()->count(1))->create(['user_type' => 'admin']);
        $userTwo = User::factory()->hasAttached(Farm::factory()->count(1))->create(['user_type' => 'admin']);

        Sanctum::actingAs($userOne);

        $farm = $userTwo->farms()->first();

        $updatedArea = $this->faker->numberBetween(100, 10000);
        $response = $this->putJson("api/admin/farm/" . $farm->id, ['area_of_hector' => $updatedArea]);

        $response->dump();
        $response->assertForbidden();
        $response->assertJson(function(AssertableJson $json) use ($farm, $updatedArea){
            $json
                ->has('code')
                ->has('message')
                ->where('code', 403)
                ->has('data');
        });
    }

}
