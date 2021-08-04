<?php

namespace Tests\Feature\Http\Controllers\Api\Admin;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_cannot_register_without_email()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->make(['email' => '', 'phone_no' => '+928008766']);

        $response = $this->post('/api/admin/register', $user->toArray());

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'data' => [
                'email' => [],
                'password' => [],
                'device_token' => [],
                'device_name' => [],
                'phone_no' => []
            ],
            'code',
            'message'
        ]);
        $response->assertJsonPath('code', 422);
        $response->assertJsonPath('message', 'The given data was invalid.');
        $response->assertJsonPath('data.email.0', 'The email field is required.');
    }
}
