<?php

namespace Tests\Feature\Http\Controllers\Api\Admin;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileControllerTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    public function test_user_cannot_update_profile_without_token()
    {
        $response = $this->putJson("/api/admin/profile");
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

        $response = $this->putJson("/api/admin/profile");
        $response->assertStatus(403);
        $response->assertJson(function(AssertableJson $json){
            $json
                ->has('message')
                ->where('message', 'Forbidden.')
                ->where('code', 403)
                ->has('data');
        });
    }

    public function test_user_cannot_update_profile_without_email()
    {
        // $this->withoutExceptionHandling();
        $user = Sanctum::actingAs(
            User::factory()->create(['user_type' => 'admin']),
            ['*']
        );

        $response = $this->putJson('/api/admin/profile', ['email' => '']);

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

    public function test_user_cannot_update_profile_with_invalid_email()
    {
        $user = Sanctum::actingAs(
            User::factory()->create(['user_type' => 'admin']),
            ['*']
        );

        $response = $this->putJson('/api/admin/profile', ['email' => 'adhmad@']);

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

    public function test_user_cannot_update_profile_with_existing_email()
    {
        $userOne = User::factory()->create();
        $user = Sanctum::actingAs(
            User::factory()->create(['user_type' => 'admin']),
            ['*']
        );

        $response = $this->putJson('/api/admin/profile', ['email' => $userOne->email]);

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
        $response->assertJsonPath('data.email.0', 'The email has already been taken.');
    }

    public function test_user_cannot_update_profile_without_first_name()
    {
        // $this->withoutExceptionHandling();
        $user = Sanctum::actingAs(
            User::factory()->create(['user_type' => 'admin']),
            ['*']
        );

        $response = $this->putJson('/api/admin/profile', ['first_name' => '']);

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
        $response->assertJsonPath('data.first_name.0', 'The first name must be a string.');
    }

    public function test_user_cannot_update_profile_without_last_name()
    {
        // $this->withoutExceptionHandling();
        $user = Sanctum::actingAs(
            User::factory()->create(['user_type' => 'admin']),
            ['*']
        );

        $response = $this->putJson('/api/admin/profile', ['last_name' => '']);

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
        $response->assertJsonPath('data.last_name.0', 'The last name must be a string.');
    }

    public function test_user_cannot_update_profile_without_phone()
    {
        // $this->withoutExceptionHandling();
        $user = Sanctum::actingAs(
            User::factory()->create(['user_type' => 'admin']),
            ['*']
        );

        $response = $this->putJson('/api/admin/profile', ['phone_no' => '']);

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
        $response->assertJsonPath('data.phone_no.0', 'The phone no must be a string.');
    }

    public function test_user_cannot_update_profile_with_invalid_phone()
    {
        // $this->withoutExceptionHandling();
        $user = Sanctum::actingAs(
            User::factory()->create(['user_type' => 'admin']),
            ['*']
        );

        $response = $this->putJson('/api/admin/profile', ['phone_no' => '+928877']);

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

    public function test_user_cannot_update_profile_without_experience()
    {
        // $this->withoutExceptionHandling();
        $user = Sanctum::actingAs(
            User::factory()->create(['user_type' => 'admin']),
            ['*']
        );

        $response = $this->putJson('/api/admin/profile', ['experience' => '']);

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
        $response->assertJsonPath('data.experience.0', 'The experience must be a string.');
    }

    public function test_user_cannot_update_profile_without_password()
    {
        // $this->withoutExceptionHandling();
        $user = Sanctum::actingAs(
            User::factory()->create(['user_type' => 'admin']),
            ['*']
        );

        $response = $this->putJson('/api/admin/profile', ['password' => '']);

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
        $response->assertJsonPath('data.password.0', 'The password must be at least 6 characters.');
    }

    public function test_user_cannot_update_profile_without_password_confirmation()
    {
        // $this->withoutExceptionHandling();
        $user = Sanctum::actingAs(
            User::factory()->create(['user_type' => 'admin']),
            ['*']
        );

        $response = $this->putJson('/api/admin/profile', ['password' => 'admin123']);

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

    public function test_user_can_update_email()
    {
        /** @var App\Models\User $user */
        $user = Sanctum::actingAs(
            User::factory()->create(['user_type' => 'admin']),
            ['*']
        );

        $email = $this->faker->email();

        $response = $this->putJson('/api/admin/profile', ['email' => $email]);

        $user->refresh();

        $response->assertStatus(200);

        $response->assertJson(function(AssertableJson $json) use ($user, $email){
            $json
                ->has('data', function(AssertableJson $json) use ($user, $email){
                    $json
                        ->where('id', $user->id)
                        ->where('first_name', $user->first_name)
                        ->where('last_name', $user->last_name)
                        ->where('email', $user->email)
                        ->where('phone_no', $user->phone_no)
                        ->where('experience', $user->experience)
                        ->has('email_verified_at')
                        ->has('created_at')
                        ->has('updated_at');
                })
                ->has('message')
                ->where('message', null)
                ->has('code')
                ->where('code', 200);
        });

        $this->assertEquals($user->email, $email);
    }

    public function test_user_can_update_first_name()
    {
        /** @var App\Models\User $user */
        $user = Sanctum::actingAs(
            User::factory()->create(['user_type' => 'admin']),
            ['*']
        );

        $first_name = $this->faker->firstName();

        $response = $this->putJson('/api/admin/profile', ['first_name' => $first_name]);

        $user->refresh();

        $response->assertStatus(200);

        $response->assertJson(function(AssertableJson $json) use ($user){
            $json
                ->has('data', function(AssertableJson $json) use ($user){
                    $json
                        ->where('id', $user->id)
                        ->where('first_name', $user->first_name)
                        ->where('last_name', $user->last_name)
                        ->where('email', $user->email)
                        ->where('phone_no', $user->phone_no)
                        ->where('experience', $user->experience)
                        ->has('email_verified_at')
                        ->has('created_at')
                        ->has('updated_at');
                })
                ->has('message')
                ->where('message', null)
                ->has('code')
                ->where('code', 200);
        });

        $this->assertEquals($user->first_name, $first_name);
    }

    public function test_user_can_update_last_name()
    {
        /** @var App\Models\User $user */
        $user = Sanctum::actingAs(
            User::factory()->create(['user_type' => 'admin']),
            ['*']
        );

        $last_name = $this->faker->lastName();

        $response = $this->putJson('/api/admin/profile', ['last_name' => $last_name]);

        $user->refresh();

        $response->assertStatus(200);

        $response->assertJson(function(AssertableJson $json) use ($user){
            $json
                ->has('data', function(AssertableJson $json) use ($user){
                    $json
                        ->where('id', $user->id)
                        ->where('first_name', $user->first_name)
                        ->where('last_name', $user->last_name)
                        ->where('email', $user->email)
                        ->where('phone_no', $user->phone_no)
                        ->where('experience', $user->experience)
                        ->has('email_verified_at')
                        ->has('created_at')
                        ->has('updated_at');
                })
                ->has('message')
                ->where('message', null)
                ->has('code')
                ->where('code', 200);
        });

        $this->assertEquals($user->last_name, $last_name);
    }

    public function test_user_can_update_phone()
    {
        /** @var App\Models\User $user */
        $user = Sanctum::actingAs(
            User::factory()->create(['user_type' => 'admin']),
            ['*']
        );

        $phone = $this->faker->e164PhoneNumber();

        $response = $this->putJson('/api/admin/profile', ['phone_no' => $phone]);

        $user->refresh();

        $response->assertStatus(200);

        $response->assertJson(function(AssertableJson $json) use ($user){
            $json
                ->has('data', function(AssertableJson $json) use ($user){
                    $json
                        ->where('id', $user->id)
                        ->where('first_name', $user->first_name)
                        ->where('last_name', $user->last_name)
                        ->where('email', $user->email)
                        ->where('phone_no', $user->phone_no)
                        ->where('experience', $user->experience)
                        ->has('email_verified_at')
                        ->has('created_at')
                        ->has('updated_at');
                })
                ->has('message')
                ->where('message', null)
                ->has('code')
                ->where('code', 200);
        });

        $this->assertEquals($user->phone_no, $phone);
    }

    public function test_user_can_update_experience()
    {
        /** @var App\Models\User $user */
        $user = Sanctum::actingAs(
            User::factory()->create(['user_type' => 'admin']),
            ['*']
        );

        $experience = $this->faker->sentence();

        $response = $this->putJson('/api/admin/profile', ['experience' => $experience]);

        $user->refresh();

        $response->assertStatus(200);

        $response->assertJson(function(AssertableJson $json) use ($user){
            $json
                ->has('data', function(AssertableJson $json) use ($user){
                    $json
                        ->where('id', $user->id)
                        ->where('first_name', $user->first_name)
                        ->where('last_name', $user->last_name)
                        ->where('email', $user->email)
                        ->where('phone_no', $user->phone_no)
                        ->where('experience', $user->experience)
                        ->has('email_verified_at')
                        ->has('created_at')
                        ->has('updated_at');
                })
                ->has('message')
                ->where('message', null)
                ->has('code')
                ->where('code', 200);
        });

        $this->assertEquals($user->experience, $experience);
    }

    public function test_user_can_update_image()
    {
        /** @var App\Models\User $user */
        $user = Sanctum::actingAs(
            User::factory()->create(['user_type' => 'admin']),
            ['*']
        );

        $response = $this->putJson('/api/admin/profile', ['image' => UploadedFile::fake()->image('avatar.jpg')]);

        $user->refresh();

        $response->assertStatus(200);

        $response->assertJson(function(AssertableJson $json) use ($user){
            $json
                ->has('data', function(AssertableJson $json) use ($user){
                    $json
                        ->where('id', $user->id)
                        ->where('first_name', $user->first_name)
                        ->where('last_name', $user->last_name)
                        ->where('email', $user->email)
                        ->where('phone_no', $user->phone_no)
                        ->where('experience', $user->experience)
                        ->where('image', asset('avatar.jpg'))
                        ->has('email_verified_at')
                        ->has('created_at')
                        ->has('updated_at');
                })
                ->has('message')
                ->where('message', null)
                ->has('code')
                ->where('code', 200);
        });
    }

    public function test_user_can_update_password()
    {
        /** @var App\Models\User $user */
        $user = Sanctum::actingAs(
            User::factory()->create(['user_type' => 'admin']),
            ['*']
        );

        $response = $this->post('/api/admin/login', ['email' => $user->email, 'device_token' => 'token', 'device_name' => 'device name', 'password' => 'admin123']);

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
                        ->has('email_verified_at')
                        ->where('farms', [])
                        ->where('active_subscription', null)
                        ->has('created_at')
                        ->has('updated_at')
                        ->has('image')
                        ->missing('password')
                        ->missing('remember_token');
                })
                ->has('data.token')
                ->has('code')
                ->has('message')
                ->where('code', 200);
        });

        $password = $this->faker->password();

        $response = $this->putJson('/api/admin/profile', ['password' => $password, 'password_confirmation' => $password]);

        $user->refresh();

        $response->assertStatus(200);

        $response->assertJson(function(AssertableJson $json) use ($user){
            $json
                ->has('data', function(AssertableJson $json) use ($user){
                    $json
                        ->where('id', $user->id)
                        ->where('first_name', $user->first_name)
                        ->where('last_name', $user->last_name)
                        ->where('email', $user->email)
                        ->where('phone_no', $user->phone_no)
                        ->where('experience', $user->experience)
                        ->has('email_verified_at')
                        ->has('created_at')
                        ->has('updated_at');
                })
                ->has('message')
                ->where('message', null)
                ->has('code')
                ->where('code', 200);
        });

        $response = $this->post('/api/admin/login', ['email' => $user->email, 'device_token' => 'token', 'device_name' => 'device name', 'password' => 'admin123']);

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


        $response = $this->post('/api/admin/login', ['email' => $user->email, 'device_token' => 'token', 'device_name' => 'device name', 'password' => $password]);

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
                        ->has('email_verified_at')
                        ->where('farms', [])
                        ->where('active_subscription', null)
                        ->has('image')
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
}
