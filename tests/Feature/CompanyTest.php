<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Company;
use App\Models\User;
use App\Models\Anime;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    private Company $company;
    private Anime $anime;
    private Anime $anime1;
    private User $user;
    private User $user1;
    private User $user2;

    protected function setUp(): void
    {
        parent::setUp();
        $this->company = Company::factory()->create();
        $this->anime = Anime::factory()->create();
        $this->anime1 = Anime::factory()->create();
        $this->user = User::factory()->create();
        $this->user1 = User::factory()->create();
        $this->user2 = User::factory()->create();

        $this->anime->companies()->attach($this->company->id);
        $this->anime1->companies()->attach($this->company->id);

        $this->anime->reviewUsers()->attach($this->user1->id, ['score' => 100]);
        $this->anime1->reviewUsers()->attach($this->user1->id, ['score' => 90]);
        $this->anime->reviewUsers()->attach($this->user2->id, ['score' => 80]);
    }

    /**
     * 会社ページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testCompanyView()
    {
        $response = $this->get(route('company.show', ['company_id' => $this->company->id]));
        $response->assertStatus(200);
    }

    /**
     * 会社ページの情報の表示のテスト
     *
     * @test
     * @return void
     */
    public function testCompanyInformationView()
    {
        $response = $this->get(route('company.show', ['company_id' => $this->company->id]));
        $response->assertSeeInOrder([
            $this->company->name,
            '計2本',
            $this->anime->title,
            $this->anime->median,
            $this->anime->count,
            $this->anime1->title,
        ]);
    }

    /**
     * ゲスト時の会社ページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testGuestCompanyView()
    {
        $response = $this->get(route('company.show', ['company_id' => $this->company->id]));
        $response->assertDontSee('つけた得点');
    }

    /**
     * ログイン時の会社ページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testUser1LoginView()
    {
        $this->actingAs($this->user);
        $response = $this->get(route('company.show', ['company_id' => $this->company->id]));
        $response->assertSee('つけた得点');
    }

    /**
     * 会社ページの統計情報の表示のテスト
     *
     * @test
     * @return void
     */
    public function testCompanyStatisticsView()
    {
        $response = $this->get(route('company.show', ['company_id' => $this->company->id]));
        $response->assertSeeInOrder([
            '中央値',
            90,
            '平均値',
            90,
            '総得点数',
            3,
            'ユーザー数',
            2,
        ]);
    }
    /**
     * 存在しない会社ページにアクセスしたときのテスト
     *
     * @test
     * @return void
     */
    public function testNotExistCompanyView()
    {
        $response = $this->get(route('company.show', ['company_id' => 33333333333333]));
        $response->assertStatus(404);
    }

    /**
     * 制作会社リストの表示のテスト
     *
     * @test
     * @return void
     */
    public function testCompanyListView()
    {
        $response = $this->get(route('company_list.show'));
        $response->assertStatus(200);
    }
}
