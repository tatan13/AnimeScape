<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * 会員登録ページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testRegisterView()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);

        $this->actingAs($this->user);
        $this->get('/register')->assertRedirect('/');
    }

    /**
     * 会員登録ページを入力した場合のテスト
     *
     * @test
     * @return void
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
     * 間違った会員登録情報を入力した場合のテスト
     *
     * @test
     * @return void
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
