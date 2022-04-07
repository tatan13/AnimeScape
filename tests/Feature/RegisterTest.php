<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
    * @test
    */
    public function testRegisterView()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);

        $this->actingAs($this->user);
        $this->get('/register')->assertRedirect('/');
    }

    /**
    * @test
    */
    public function testRegisterCorrectPost()
    {
        $response = $this->post('/register', [
            'uid' => 'user',
            'password' => 'secretpassword',
            'password_confirmation' => 'secretpassword',
        ]);
        $response->assertLocation('/');
        $this->assertDatabaseHas('users', [
            'uid' => 'user',
        ]);
        $this->assertAuthenticated();

        $this->post('/logout');
        $this->post('/login', [
            'uid' => 'user',
            'password' => 'secretpassword',
        ]);
        $this->assertAuthenticated();
    }

    /**
    * @test
    */
    public function testRegisterIncorrectPost()
    {
        $response = $this->from('/register')->post('/register', [
            'uid' => $this->user->id,
            'password' => 'secretpassword',
            'password_confirmation' => 'secretpassword',
        ])->assertRedirect('/register');
        $this->assertGuest();
    }
}
