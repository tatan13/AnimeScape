<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Anime;
use App\Models\User;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * インデックスページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testIndexView()
    {
        $response = $this->get(route('index.show'));
        $response->assertStatus(200);
    }

    /**
     * ゲスト時のインデックスページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testGuestIndexView()
    {
        $response = $this->get(route('index.show'));
        $response->assertStatus(200);
        $response->assertSee('新規ID作成');
        $response->assertDontSee('管理者メニュー');
        $response->assertDontSee('つけた得点');
    }

    /**
     * ログイン時のインデックスページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testLoginIndexView()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('index.show'));
        $response->assertDontSee('管理者メニュー');
        $response->assertSeeInOrder([
            'ログイン中',
            'つけた得点',
        ]);
    }

    /**
     * ルートログイン時のインデックスページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testRootLoginIndexView()
    {
        $user = User::factory()->create(['name' => 'root']);
        $this->actingAs($user);
        $response = $this->get(route('index.show'));
        $response->assertSee('管理者メニュー');
    }

    /**
     * インデックスページのアニメ情報表示のテスト
     *
     * @test
     * @return void
     */
    public function testIndexAnimeView()
    {
        Anime::factory()->create(['title' => '霊剣山1', 'median' => 100, 'count' => 200]);
        Anime::factory()->create(['title' => '霊剣山2', 'median' => 0, 'count' => 300]);
        $response = $this->get(route('index.show'));
        $response->assertStatus(200);
        $response->assertSeeInOrder(['霊剣山1', 100, 200, '霊剣山2', 0, 300]);
    }

    /**
     * 更新履歴ページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testUpdateLogView()
    {
        $response = $this->get(route('update_log.show'));
        $response->assertStatus(200);
    }

    /**
     * プライバシーポリシーページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testPrivacyPolicyView()
    {
        $response = $this->get(route('privacy_policy.show'));
        $response->assertStatus(200);
    }

    /**
     * このサイトについての説明ページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testSiteInformationView()
    {
        $response = $this->get(route('site_information.show'));
        $response->assertStatus(200);
    }
}
