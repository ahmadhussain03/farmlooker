<?php

namespace Tests\Feature\Http\Controllers\Api\Admin;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withHeaders([
            'Accept' => 'application/json'
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_cannot_register_without_email()
    {
        // $this->withoutExceptionHandling();
        $user = $this->getUserData();
        $user['email'] = "";

        $response = $this->post('/api/admin/register', $user);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'data' => [
                'email' => []
            ],
            'code',
            'message'
        ]);
        $response->assertJsonPath('code', 422);
        $response->assertJsonPath('message', 'The given data was invalid.');
        $response->assertJsonPath('data.email.0', 'The email field is required.');
    }

    public function test_user_cannot_register_with_invalid_email()
    {
        // $this->withoutExceptionHandling();
        $user = $this->getUserData();
        $user['email'] = 'email';

        $response = $this->post('/api/admin/register', $user);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'data' => [
                'email' => []
            ],
            'code',
            'message'
        ]);
        $response->assertJsonPath('code', 422);
        $response->assertJsonPath('message', 'The given data was invalid.');
        $response->assertJsonPath('data.email.0', 'The email must be a valid email address.');
    }

    public function test_user_cannot_register_without_first_name()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->make(['first_name' => '']);

        $response = $this->post('/api/admin/register', $user->toArray());

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'data' => [
                'first_name' => []
            ],
            'code',
            'message'
        ]);
        $response->assertJsonPath('code', 422);
        $response->assertJsonPath('message', 'The given data was invalid.');
        $response->assertJsonPath('data.first_name.0', 'The first name field is required.');
    }

    public function test_user_cannot_register_without_last_name()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->make(['last_name' => '']);

        $response = $this->post('/api/admin/register', $user->toArray());

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'data' => [
                'last_name' => []
            ],
            'code',
            'message'
        ]);
        $response->assertJsonPath('code', 422);
        $response->assertJsonPath('message', 'The given data was invalid.');
        $response->assertJsonPath('data.last_name.0', 'The last name field is required.');
    }

    public function test_user_cannot_register_without_phone()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->make(['phone_no' => '']);

        $response = $this->post('/api/admin/register', $user->toArray());

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'data' => [
                'phone_no' => []
            ],
            'code',
            'message'
        ]);
        $response->assertJsonPath('code', 422);
        $response->assertJsonPath('message', 'The given data was invalid.');
        $response->assertJsonPath('data.phone_no.0', 'The phone no field is required.');
    }

    public function test_user_cannot_register_with_invalid_phone()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->make(['phone_no' => '+928877']);

        $response = $this->post('/api/admin/register', $user->toArray());

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'data' => [
                'phone_no' => []
            ],
            'code',
            'message'
        ]);
        $response->assertJsonPath('code', 422);
        $response->assertJsonPath('message', 'The given data was invalid.');
        $response->assertJsonPath('data.phone_no.0', 'validation.phone');
    }

    public function test_user_cannot_register_without_experience()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->make(['experience' => '']);

        $response = $this->post('/api/admin/register', $user->toArray());

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'data' => [
                'experience' => []
            ],
            'code',
            'message'
        ]);
        $response->assertJsonPath('code', 422);
        $response->assertJsonPath('message', 'The given data was invalid.');
        $response->assertJsonPath('data.experience.0', 'The experience field is required.');
    }

    public function test_user_cannot_register_without_device_name()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->make(['device_name' => '']);

        $response = $this->post('/api/admin/register', $user->toArray());

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'data' => [
                'device_name' => []
            ],
            'code',
            'message'
        ]);
        $response->assertJsonPath('code', 422);
        $response->assertJsonPath('message', 'The given data was invalid.');
        $response->assertJsonPath('data.device_name.0', 'The device name field is required.');
    }

    public function test_user_cannot_register_without_device_token()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->make(['device_token' => '']);

        $response = $this->post('/api/admin/register', $user->toArray());

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'data' => [
                'device_token' => []
            ],
            'code',
            'message'
        ]);
        $response->assertJsonPath('code', 422);
        $response->assertJsonPath('message', 'The given data was invalid.');
        $response->assertJsonPath('data.device_token.0', 'The device token field is required.');
    }

    public function test_user_cannot_register_without_password()
    {
        // $this->withoutExceptionHandling();
        $user = $this->getUserData();
        $user['password'] = '';

        $response = $this->post('/api/admin/register', $user);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'data' => [
                'password' => []
            ],
            'code',
            'message'
        ]);
        $response->assertJsonPath('code', 422);
        $response->assertJsonPath('message', 'The given data was invalid.');
        $response->assertJsonPath('data.password.0', 'The password field is required.');
    }

    public function test_user_cannot_register_without_password_confirmation()
    {
        // $this->withoutExceptionHandling();
        $user = $this->getUserData();
        $user['password_confirmation'] = '';

        $response = $this->post('/api/admin/register', $user);

        $response->assertStatus(422);

        $response->assertJson(function(AssertableJson $json){
            $json
                ->has('data')
                ->has('data.password', 1)
                ->where('data.password.0', 'The password confirmation does not match.')
                ->has('message')
                ->where('message', 'The given data was invalid.')
                ->has('code')
                ->where('code', 422);
        });
    }

    public function test_user_can_register()
    {
        $user = $this->getUserData();

        $response = $this->postJson('/api/admin/register', $user);

        $response->assertStatus(200);

        $response->assertJson(function(AssertableJson $json) use ($user){
            $json
                ->has('data')
                ->has('data.user', function(AssertableJson $json) use ($user){
                    $json
                        ->where('id', 1)
                        ->where('first_name', $user['first_name'])
                        ->where('last_name', $user['last_name'])
                        ->where('experience', $user['experience'])
                        ->where('email', $user['email'])
                        ->where('phone_no', $user['phone_no'])
                        ->where('farms', [])
                        ->where('active_subscription', null)
                        ->has('created_at')
                        ->has('updated_at')
                        ->missing('password')
                        ->missing('remember_token');
                })
                ->has('data.token')
                ->has('code')
                ->has('message')
                ->where('code', 200);
        });
    }

    public function test_user_cannot_register_if_email_already_exists()
    {
        // $this->withoutExceptionHandling();
        $email = "test@test.com";
        User::factory()->create(['email' => $email]);
        $user = $this->getUserData();
        $user['email'] = $email;

        $response = $this->post('/api/admin/register', $user);

        $response->assertStatus(422);

        $response->assertJson(function(AssertableJson $json){
            $json
                ->has('data')
                ->has('data.email', 1)
                ->where('data.email.0', 'The email has already been taken.')
                ->has('message')
                ->where('message', 'The given data was invalid.')
                ->has('code')
                ->where('code', 422);
        });
    }

    public function test_user_cannot_login_without_email()
    {
        // $this->withoutExceptionHandling();

        $response = $this->post('/api/admin/login', ['email' => '', 'device_token' => 'token', 'device_name' => 'test', 'password' => 'admin123']);

        $response->assertStatus(422);

        $response->assertJson(function(AssertableJson $json){
            $json
                ->has('data')
                ->has('data.email', 1)
                ->where('data.email.0', 'The email field is required.')
                ->has('message')
                ->where('message', 'The given data was invalid.')
                ->has('code')
                ->where('code', 422);
        });
    }

    public function test_user_cannot_login_without_password()
    {
        // $this->withoutExceptionHandling();
        $user = $this->getLoginUserData();

        $response = $this->post('/api/admin/login', ['email' => $user['email'], 'device_token' => 'token', 'device_name' => 'test', 'password' => '']);

        $response->assertStatus(422);

        $response->assertJson(function(AssertableJson $json){
            $json
                ->has('data')
                ->has('data.password', 1)
                ->where('data.password.0', 'The password field is required.')
                ->has('message')
                ->where('message', 'The given data was invalid.')
                ->has('code')
                ->where('code', 422);
        });
    }

    public function test_user_cannot_login_without_device_token()
    {
        // $this->withoutExceptionHandling();
        $user = $this->getLoginUserData();

        $response = $this->post('/api/admin/login', ['email' => $user['email'], 'device_token' => '', 'device_name' => 'test', 'password' => 'admin123']);

        $response->assertStatus(422);

        $response->assertJson(function(AssertableJson $json){
            $json
                ->has('data')
                ->has('data.device_token', 1)
                ->where('data.device_token.0', 'The device token field is required.')
                ->has('message')
                ->where('message', 'The given data was invalid.')
                ->has('code')
                ->where('code', 422);
        });
    }

    public function test_user_cannot_login_without_device_name()
    {
        // $this->withoutExceptionHandling();
        $user = $this->getLoginUserData();

        $response = $this->post('/api/admin/login', ['email' => $user['email'], 'device_token' => 'token', 'device_name' => '', 'password' => 'admin123']);

        $response->assertStatus(422);

        $response->assertJson(function(AssertableJson $json){
            $json
                ->has('data')
                ->has('data.device_name', 1)
                ->where('data.device_name.0', 'The device name field is required.')
                ->has('message')
                ->where('message', 'The given data was invalid.')
                ->has('code')
                ->where('code', 422);
        });
    }

    public function test_user_cannot_login_with_invalid_email()
    {
        // $this->withoutExceptionHandling();

        $response = $this->post('/api/admin/login', ['email' => 'email', 'device_token' => 'token', 'device_name' => 'device name', 'password' => 'admin123']);

        $response->assertStatus(422);

        $response->assertJson(function(AssertableJson $json){
            $json
                ->has('data')
                ->has('data.email', 1)
                ->where('data.email.0', 'The email must be a valid email address.')
                ->has('message')
                ->where('message', 'The given data was invalid.')
                ->has('code')
                ->where('code', 422);
        });
    }

    public function test_user_cannot_login_with_invalid_password()
    {
        // $this->withoutExceptionHandling();
        $user = $this->getLoginUserData();

        $response = $this->post('/api/admin/login', ['email' => $user['email'], 'device_token' => 'token', 'device_name' => 'device name', 'password' => 'admin1234']);

        $response->assertStatus(422);

        $response->assertJson(function(AssertableJson $json){
            $json
                ->has('data')
                ->has('data.email', 1)
                ->where('data.email.0', 'The provided credentials are incorrect.')
                ->has('message')
                ->where('message', 'The given data was invalid.')
                ->has('code')
                ->where('code', 422);
        });
    }

    public function test_user_can_login()
    {
        // $this->withoutExceptionHandling();
        $user = $this->getLoginUserData();

        $response = $this->post('/api/admin/login', ['email' => $user['email'], 'device_token' => 'token', 'device_name' => 'device name', 'password' => 'admin123']);

        $response->assertStatus(200);

        $response->assertJson(function(AssertableJson $json) use ($user){
            $json
                ->has('data')
                ->has('data.user', function(AssertableJson $json) use ($user){
                    $json
                        ->where('id', $user['id'])
                        ->where('first_name', $user['first_name'])
                        ->where('last_name', $user['last_name'])
                        ->where('experience', $user['experience'])
                        ->where('email', $user['email'])
                        ->where('phone_no', $user['phone_no'])
                        ->where('email_verified_at', $user['email_verified_at'])
                        ->where('farms', [])
                        ->where('active_subscription', null)
                        ->has('created_at')
                        ->has('updated_at')
                        ->missing('password')
                        ->missing('remember_token');
                })
                ->has('data.token')
                ->has('code')
                ->has('message')
                ->where('code', 200);
        });
    }

    public function test_user_cannot_get_detail_without_token()
    {
        $response = $this->getJson("/api/admin/user");
        $response->assertStatus(401);
        $response->assertJson(function(AssertableJson $json){
            $json
                ->has('message')
                ->where('message', 'Unauthenticated.')
                ->where('code', 401)
                ->has('data');
        });

    }

    public function test_user_cannot_get_detail_unless_admin()
    {
        // $this->withoutExceptionHandling();
        $user = Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $response = $this->getJson("/api/admin/user");
        $response->assertStatus(403);
        $response->assertJson(function(AssertableJson $json){
            $json
                ->has('message')
                ->where('message', 'Forbidden.')
                ->where('code', 403)
                ->has('data');
        });

    }

    public function test_get_authenticated_user()
    {
        $user = Sanctum::actingAs(
            User::factory()->create(['user_type' => 'admin']),
            ['*']
        );

        $response = $this->getJson('/api/admin/user');

        $response->assertStatus(200);
        $response->assertJson(function(AssertableJson $json) use ($user){
            $json
                ->has('data')
                ->has('data', function(AssertableJson $json) use ($user){
                    $json
                        ->where('id', $user['id'])
                        ->where('first_name', $user->first_name)
                        ->where('last_name', $user->last_name)
                        ->where('experience', $user->experience)
                        ->where('email', $user->email)
                        ->where('phone_no', $user->phone_no)
                        ->has('email_verified_at')
                        ->where('farms', [])
                        ->where('active_subscription', null)
                        ->has('created_at')
                        ->has('updated_at')
                        ->missing('password')
                        ->missing('remember_token');
                })
                ->has('code')
                ->has('message')
                ->where('code', 200);
        });
    }

    public function test_user_cannot_logout_without_token()
    {
        $response = $this->postJson("/api/admin/logout");
        $response->assertStatus(401);
        $response->assertJson(function(AssertableJson $json){
            $json
                ->has('message')
                ->where('message', 'Unauthenticated.')
                ->where('code', 401)
                ->has('data');
        });
    }

    public function test_user_can_logout()
    {
        $user = Sanctum::actingAs(
            User::factory()->create(['user_type' => 'admin']),
            ['*']
        );

        $response = $this->postJson("/api/admin/logout");
        $response->assertOk();
    }


    public function getUserData()
    {
        $user = User::factory()->make(['password' => 'admin123']);
        $userData = $user->toArray();
        $userData['password'] = 'admin123';
        $userData['password_confirmation'] = 'admin123';
        $userData['phone_no'] = '+923007438117';
        $userData['device_token'] = 'token';
        $userData['device_name'] = 'testing';

        return $userData;
    }

    public function getLoginUserData($data = [])
    {
        $user = User::factory()->create();
        $userData = $user->toArray();

        return array_merge($userData, $data);

    }
}
