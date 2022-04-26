<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Anime;
use App\Models\UserReview;
use App\Models\User;
use Tests\TestCase;

class StatisticsTest extends TestCase
{
    use RefreshDatabase;

    private Anime $anime1;
    private Anime $anime2;
    private Anime $anime3;
    private Anime $anime4;

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
        $response->assertSeeInOrder([
            '（中央値順）',
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
        $response->assertSeeInOrder([
            '（平均値順）',
            $this->anime4->title,
            $this->anime3->title,
            $this->anime2->title,
            $this->anime1->title
        ]);
    }

    /**
     * すべてのアニメのデータ数順ランキングページのテスト
     *
     * @test
     * @return void
     */
    public function testAllStatisticsCountView()
    {
        $response = $this->get(route('anime_statistics.show', ['count' => 2, 'category' => Anime::TYPE_COUNT]));
        $response->assertSeeInOrder([
            '（データ数順）',
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
        $response->assertSeeInOrder([
            '（中央値順）',
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
        $response->assertSeeInOrder([
            '（中央値順）',
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
