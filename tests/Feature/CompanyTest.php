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
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->company = Company::factory()->create();
        $this->anime = Anime::factory()->create();
        $this->user = User::factory()->create();

        $this->anime->companies()->attach($this->company->id);
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
            '計1本',
            $this->anime->title,
            $this->anime->median,
            $this->anime->count,
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
}
