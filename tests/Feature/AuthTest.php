<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $response = $this->postJson('/api/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone_number' => '+1234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
                ->assertJson([
                    'success' => true,
                    'message' => 'User registered successfully',
                ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);
    }

    public function test_user_can_login_with_email(): void
    {
        $user = User::create([
            'name' => 'John Doe',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone_number' => '+1234567890',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'login' => 'john@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Login successful',
                ])
                ->assertJsonStructure([
                    'data' => [
                        'user',
                        'token',
                        'token_type'
                    ]
                ]);
    }

    public function test_user_can_login_with_phone(): void
    {
        $user = User::create([
            'name' => 'John Doe',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone_number' => '+1234567890',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'login' => '+1234567890',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Login successful',
                ]);
    }

    public function test_authenticated_user_can_access_profile(): void
    {
        $user = User::create([
            'name' => 'John Doe',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone_number' => '+1234567890',
            'password' => bcrypt('password123'),
        ]);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/profile');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                ])
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'name',
                        'email',
                        'first_name',
                        'last_name',
                    ]
                ]);
    }
}
