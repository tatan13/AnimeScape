<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * ログインページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testLoginView()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /**
     * ログイン時のログインページアクセス時のリダイレクトテスト
     *
     * @test
     * @return void
     */
    public function testUserLoginLoginRedirect()
    {
        $this->actingAs($this->user);
        $this->get('/login')->assertRedirect('/');
    }

    /**
     * 正しいログイン情報を入力した場合のテスト
     *
     * @test
     * @return void
     */
    public function testLoginCorrectPost()
    {
        $response = $this->post('/login', [
            'uid' => $this->user->uid,
            'password' => 'secret',
        ]);
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($this->user);
    }

    /**
     * 間違ったログイン情報を入力した場合のテスト
     *
     * @test
     * @return void
     */
    public function testLoginIncorrectPost()
    {
        $response = $this->from('/login')->post('/login', [
            'uid' => $this->user->uid,
            'password' => 'public',
        ]);
        $response->assertRedirect('/login');
        $this->assertGuest();
        $this->get('/login')->assertSee('ログインIDとパスワードが一致していません。');
    }

    /**
     * ログアウトのテスト
     *
     * @test
     * @return void
     */
    public function testLogout()
    {
        $this->actingAs($this->user);
        $response = $this->post('/logout');
        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
