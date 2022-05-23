<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Anime;
use App\Models\User;
use App\Models\Company;
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
        $response->assertSeeInOrder([
            'ログイン中',
            'つけた得点',
        ]);
    }

    /**
     * インデックスページのアニメ情報表示のテスト
     *
     * @test
     * @return void
     */
    public function testIndexAnimeView()
    {
        $anime1 = Anime::factory()->create(['median' => 100, 'count' => 200]);
        $anime2 = Anime::factory()->create(['median' => 0, 'count' => 300]);
        $company = Company::factory()->create();
        $anime1->companies()->attach($company->id);
        $response = $this->get(route('index.show'));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $anime1->title,
            $anime1->companies[0]->name,
            $anime1->median,
            $anime1->count,
            $anime2->title,
            $anime2->median,
            $anime2->count,
        ]);
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
