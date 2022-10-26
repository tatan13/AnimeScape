<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Anime;
use App\Models\User;
use Tests\TestCase;

class StatisticsTest extends TestCase
{
    use RefreshDatabase;

    private Anime $anime1;
    private Anime $anime2;
    private Anime $anime3;
    private Anime $anime4;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->anime1 = Anime::factory()->create([
            'year' => 2022,
            'coor' => 1,
            'median' => 75,
            'average' => 70,
            'count' => 5,
        ]);
        $this->anime2 = Anime::factory()->create([
            'year' => 2022,
            'coor' => 1,
            'median' => 74,
            'average' => 73,
            'count' => 1,
        ]);
        $this->anime3 = Anime::factory()->create([
            'year' => 2021,
            'coor' => 2,
            'median' => 73,
            'average' => 75,
            'count' => 2,
        ]);
        $this->anime4 = Anime::factory()->create([
            'year' => 2022,
            'coor' => 2,
            'median' => 72,
            'average' => 77,
            'count' => 3,
        ]);
        $this->user = User::factory()->create();
    }

    /**
     * ゲスト時のランキングページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testGuestStatisticsView()
    {
        $response = $this->get(route('anime_statistics.show', ['category' => Anime::TYPE_MEDIAN]));
        $response->assertStatus(200);
        $response->assertDontSee('つけた得点');
    }

    /**
     * ログイン時のランキングページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testLoginStatisticsView()
    {
        $this->actingAs($this->user);
        $response = $this->get(route('anime_statistics.show', ['category' => Anime::TYPE_MEDIAN]));
        $response->assertStatus(200);
        $response->assertSee('つけた得点');
    }

    /**
     * すべてのアニメの中央値順ランキングページのテスト
     *
     * @test
     * @return void
     */
    public function testAllStatisticsMedianView()
    {
        $response = $this->get(route('anime_statistics.show', ['category' => Anime::TYPE_MEDIAN]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->anime1->title,
            75,
            $this->anime2->title,
            74,
            $this->anime3->title,
            73,
            $this->anime4->title,
            72
        ]);
    }

    /**
     * すべてのアニメの平均値順ランキングページのテスト
     *
     * @test
     * @return void
     */
    public function testAllStatisticsAverageView()
    {
        $response = $this->get(route('anime_statistics.show', ['category' => Anime::TYPE_AVERAGE]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->anime4->title,
            $this->anime3->title,
            $this->anime2->title,
            $this->anime1->title
        ]);
    }

    /**
     * すべてのアニメの得点数順ランキングページのテスト
     *
     * @test
     * @return void
     */
    public function testAllStatisticsCountView()
    {
        $response = $this->get(route('anime_statistics.show', ['count' => 2, 'category' => Anime::TYPE_COUNT]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->anime1->title,
            $this->anime4->title,
            $this->anime3->title
        ]);
        $response->assertDontSee($this->anime2->title);
    }

    /**
     * 年別のアニメのランキングページのテスト
     *
     * @test
     * @return void
     */
    public function testYearStatisticsView()
    {
        $response = $this->get(route('anime_statistics.show', ['category' => Anime::TYPE_MEDIAN, 'year' => 2022]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->anime1->title,
            $this->anime2->title,
            $this->anime4->title
        ]);
        $response->assertDontSee($this->anime3->title);
    }

    /**
     * クール別のアニメのランキングページのテスト
     *
     * @test
     * @return void
     */
    public function testCoorStatisticsView()
    {
        $response = $this->get(route('anime_statistics.show', [
            'category' => Anime::TYPE_MEDIAN, 'year' => 2022, 'coor' => Anime::WINTER
        ]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->anime1->title,
            $this->anime2->title
        ]);
        $response->assertDontSee($this->anime4->title);
    }

    /**
     * クール別のアニメのランキングページのテスト
     *
     * @test
     * @return void
     */
    public function testStatisticsExceptionCategory()
    {
        $response = $this->get(route('anime_statistics.show', ['category' => 'exception']));
        $response->assertStatus(404);
    }
}
