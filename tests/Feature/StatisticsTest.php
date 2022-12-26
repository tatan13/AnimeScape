<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Anime;
use App\Models\Cast;
use App\Models\User;
use Tests\TestCase;

class StatisticsTest extends TestCase
{
    use RefreshDatabase;

    private Anime $anime1;
    private Anime $anime2;
    private Anime $anime3;
    private Anime $anime4;
    private Anime $anime5;
    private Anime $anime6;
    private Anime $anime7;
    private Anime $anime8;
    private Anime $anime9;
    private Cast $cast1;
    private Cast $cast2;
    private Cast $cast3;
    private Cast $cast4;
    private Cast $cast5;
    private Cast $cast6;
    private User $user;
    private User $user1;
    private User $user2;
    private User $user3;

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
        $this->anime5 = Anime::factory()->create();
        $this->anime6 = Anime::factory()->create();
        $this->anime7 = Anime::factory()->create();
        $this->anime8 = Anime::factory()->create();
        $this->anime9 = Anime::factory()->create();

        $this->user = User::factory()->create();
        $this->user1 = User::factory()->create();
        $this->user2 = User::factory()->create();
        $this->user3 = User::factory()->create();

        $this->cast1 = Cast::factory()->create();
        $this->cast2 = Cast::factory()->create();
        $this->cast3 = Cast::factory()->create();
        $this->cast4 = Cast::factory()->create();
        $this->cast5 = Cast::factory()->create();
        $this->cast6 = Cast::factory()->create();

        $this->anime1->actCasts()->attach($this->cast1->id);
        $this->anime2->actCasts()->attach($this->cast1->id);
        $this->anime1->actCasts()->attach($this->cast2->id);
        $this->anime3->actCasts()->attach($this->cast2->id);
        $this->anime4->actCasts()->attach($this->cast3->id);
        $this->anime5->actCasts()->attach($this->cast3->id);
        $this->anime6->actCasts()->attach($this->cast3->id);
        $this->anime7->actCasts()->attach($this->cast3->id);
        $this->anime4->actCasts()->attach($this->cast4->id);
        $this->anime8->actCasts()->attach($this->cast4->id);
        $this->anime9->actCasts()->attach($this->cast5->id);

        $this->user->likeCasts()->attach($this->cast6->id);
        $this->user1->likeCasts()->attach($this->cast6->id);
        $this->user1->likeCasts()->attach($this->cast1->id);

        $this->anime1->reviewUsers()->attach($this->user->id, [
            'score' => 100,
        ]);
        $this->anime2->reviewUsers()->attach($this->user1->id, [
            'score' => 80,
        ]);
        $this->anime3->reviewUsers()->attach($this->user1->id, [
            'score' => 50,
        ]);
        $this->anime2->reviewUsers()->attach($this->user2->id, [
            'score' => 0,
        ]);
        $this->anime4->reviewUsers()->attach($this->user->id, [
            'score' => 0,
        ]);
        $this->anime4->reviewUsers()->attach($this->user1->id, [
            'score' => 0,
        ]);
        $this->anime8->reviewUsers()->attach($this->user->id, [
            'score' => 0,
        ]);
        $this->anime8->reviewUsers()->attach($this->user1->id, [
            'score' => 0,
        ]);
        $this->anime9->reviewUsers()->attach($this->user->id, [
            'score' => 0,
        ]);
        $this->anime9->reviewUsers()->attach($this->user1->id, [
            'score' => 0,
        ]);
        $this->anime9->reviewUsers()->attach($this->user2->id, [
            'score' => 0,
        ]);
        $this->anime9->reviewUsers()->attach($this->user3->id, [
            'score' => 0,
        ]);
    }

    /**
     * ゲスト時のランキングページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testStatisticsIndexView()
    {
        $response = $this->get(route('statistics_index.show'));
        $response->assertStatus(200);
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

    /**
     * 声優ランキングページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testCastStatisticsView()
    {
        $response = $this->get(route('cast_statistics.show'));
        $response->assertStatus(200);
    }

    /**
     * すべてのアニメの中央値順声優ランキングページのテスト
     *
     * @test
     * @return void
     */
    public function testCastAllStatisticsMedianView()
    {
        $response = $this->get(route('cast_statistics.show', ['category' => 'score_median']));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->cast1->name,
            $this->cast2->name,
        ]);
    }

    /**
     * すべてのアニメの平均値順声優ランキングページのテスト
     *
     * @test
     * @return void
     */
    public function testCastAllStatisticsAverageView()
    {
        $response = $this->get(route('cast_statistics.show', ['category' => 'score_average']));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->cast2->name,
            $this->cast1->name,
        ]);
    }

    /**
     * すべてのアニメの出演数順声優ランキングページのテスト
     *
     * @test
     * @return void
     */
    public function testCastAllStatisticsActAnimesCountView()
    {
        $response = $this->get(route('cast_statistics.show', ['category' => 'act_animes_count']));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->cast3->name,
            $this->cast4->name,
        ]);
    }

    /**
     * すべてのアニメの得点数順声優ランキングページのテスト
     *
     * @test
     * @return void
     */
    public function testCastAllStatisticsScoreCountView()
    {
        $response = $this->get(route('cast_statistics.show', ['category' => 'score_count']));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->cast4->name,
            $this->cast1->name,
        ]);
    }

    /**
     * すべてのアニメの総得点ユーザー数順声優ランキングページのテスト
     *
     * @test
     * @return void
     */
    public function testCastAllStatisticsScoreUsersCountView()
    {
        $response = $this->get(route('cast_statistics.show', ['category' => 'score_users_count']));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->cast5->name,
            $this->cast1->name,
        ]);
    }

    /**
     * すべてのアニメの総得点ユーザー数順声優ランキングページのテスト
     *
     * @test
     * @return void
     */
    public function testCastAllStatisticsLikedUsersCountView()
    {
        $response = $this->get(route('cast_statistics.show', ['category' => 'liked_users_count']));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->cast6->name,
            $this->cast1->name,
        ]);
    }

    /**
     * 年別のアニメの声優ランキングページのテスト
     *
     * @test
     * @return void
     */
    public function testCastYearStatisticsView()
    {
        $response = $this->get(route('cast_statistics.show', ['category' => 'score_median', 'year' => 2021]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->cast2->name,
            $this->cast1->name,
        ]);
    }

    /**
     * クール別のアニメの声優ランキングページのテスト
     *
     * @test
     * @return void
     */
    public function testCastCoorStatisticsView()
    {
        $response = $this->get(route('cast_statistics.show', [
            'category' => 'score_median', 'year' => 2022, 'coor' => Anime::WINTER
        ]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->cast2->name,
            $this->cast1->name,
        ]);
    }

    /**
     * 得点数を絞ったアニメの声優ランキングページのテスト
     *
     * @test
     * @return void
     */
    public function testCastCountStatisticsView()
    {
        $response = $this->get(route('cast_statistics.show', [
            'category' => 'score_median', 'count' => 2,
        ]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->cast1->name,
            $this->cast2->name,
        ]);
    }

    /**
     * クール別のアニメのランキングページのテスト
     *
     * @test
     * @return void
     */
    public function testCastStatisticsExceptionCategory()
    {
        $response = $this->get(route('cast_statistics.show', ['category' => 'exception']));
        $response->assertStatus(404);
    }
}
