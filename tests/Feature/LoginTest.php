<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
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
    public function testLoginView()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $this->actingAs($this->user);
        $this->get('/login')->assertRedirect('/');
    }

    /**
    * @test 
    */
    public function testLoginCorrectPost()
    {
        $response = $this->post('/login',[
            'uid' => $this->user->uid,
            'password' => 'secret',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($this->user);
    }

    /**
    * @test 
    */
    public function testLoginIncorrectPost()
    {
        $response = $this->from('/login')->post('/login',[
            'uid' => $this->user->uid,
            'password' => 'public',
        ]);

        $response->assertRedirect('/login');
        $this->assertGuest();
        $this->get('/login')->assertSee('ログインIDとパスワードが一致していません。');
    }

    /**
    * @test 
    */
    public function testLogout()
    {
        $this->actingAs($this->user);
        $response = $this->post('/logout');
        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
