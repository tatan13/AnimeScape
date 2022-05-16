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
        $response = $this->get(route('login'));
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
        $this->get(route('login'))->assertRedirect(route('index.show'));
    }

    /**
     * 正しいログイン情報を入力した場合のテスト
     *
     * @test
     * @return void
     */
    public function testLoginCorrectPost()
    {
        $response = $this->post(route('login'), [
            'name' => $this->user->name,
            'password' => 'secret',
        ]);
        $response->assertRedirect(route('index.show'));
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
        $response = $this->from(route('login'))->post(route('login'), [
            'name' => $this->user->name,
            'password' => 'public',
        ]);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
        $this->get(route('login'))->assertSee('ログインIDとパスワードが一致していません。');
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
        $response = $this->post(route('logout'));
        $response->assertRedirect(route('index.show'));
        $this->assertGuest();
    }
}
