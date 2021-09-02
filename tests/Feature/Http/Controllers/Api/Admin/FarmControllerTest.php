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
    use RefreshDatabase;

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
}
