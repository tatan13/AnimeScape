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
    }

    /**
     * ログイン時の会員登録ページアクセス時のテスト
     *
     * @test
     * @return void
     */
    public function testUserLoginRegisterView()
    {
        $this->actingAs($this->user);
        $response = $this->get('/register');
        $response->assertRedirect('/');
    }

    /**
     * 正しい会員登録入力の場合のテスト
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
            'uid' => $this->user->uid,
            'password' => 'secre',
            'password_confirmation' => 'secret',
        ]);
        $response->assertRedirect('/register');
        $this->assertGuest();
        $this->get('/login')->assertSee([
            'その ユーザー名 は既に使用されています。',
            'パスワード は 8 文字以上で入力してください。',
            'パスワード と再入力が一致しません。',
        ]);
    }

    /**
     * null会員登録情報を入力した場合のテスト
     *
     * @test
     * @return void
     */
    public function testRegisterNullPost()
    {
        $response = $this->from('/register')->post('/register', [
            'uid' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);
        $response->assertRedirect('/register');
        $this->assertGuest();
        $this->get('/login')->assertSee([
            'ユーザー名 を入力してください。',
            'パスワード を入力してください。',
        ]);
    }
}
