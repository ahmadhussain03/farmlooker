<?php

namespace Tests\Feature\Http\Controllers\Api\Admin;

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
}
