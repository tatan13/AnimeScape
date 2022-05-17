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
        $response = $this->get(route('register'));
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
        $response = $this->get(route('register'));
        $response->assertRedirect(route('index.show'));
    }

    /**
     * 正しい会員登録入力の場合のテスト
     *
     * @test
     * @return void
     */
    public function testRegisterCorrectPost()
    {
        $response = $this->post(route('register'), [
            'name' => 'user',
            'password' => 'secretpassword',
            'password_confirmation' => 'secretpassword',
        ]);
        $response->assertLocation(route('index.show'));
        $this->assertDatabaseHas('users', [
            'name' => 'user',
        ]);
        $this->assertAuthenticated();

        $this->post(route('logout'));
        $this->post(route('login'), [
            'name' => 'user',
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
        $response = $this->from(route('register'))->post(route('register'), [
            'name' => $this->user->name,
            'password' => 'secre',
            'password_confirmation' => 'secret',
        ]);
        $response->assertRedirect(route('register'));
        $this->assertGuest();
        $this->get(route('login'))->assertSee([
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
        $response = $this->from(route('register'))->post(route('register'), [
            'name' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);
        $response->assertRedirect(route('register'));
        $this->assertGuest();
        $this->get(route('login'))->assertSee([
            'ユーザー名 を入力してください。',
            'パスワード を入力してください。',
        ]);
    }
}
