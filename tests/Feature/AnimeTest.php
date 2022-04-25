<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Anime;
use App\Models\Cast;
use App\Models\UserReview;
use App\Models\User;
use Tests\TestCase;

class AnimeTest extends TestCase
{
    use RefreshDatabase;

    private Anime $anime;
    private Cast $cast1;
    private Cast $cast2;
    private User $user1;
    private User $user2;
    private User $user3;
    private User $user4;

    protected function setUp(): void
    {
        parent::setUp();
        $this->anime = Anime::factory()->create([
            'title' => '霊剣山 叡智への資格',
            'title_short' => '霊剣山',
            'median' => 70,
            'average' => 76,
            'count' => 256,
            'max' => 100,
            'min' => 0,
        ]);

        $this->cast1 = Cast::factory()->create();
        $this->cast2 = Cast::factory()->create();

        $this->anime->actCasts()->attach($this->cast1->id);
        $this->anime->actCasts()->attach($this->cast2->id);

        $this->user1 = User::factory()->create();
        $this->user2 = User::factory()->create();
        $this->user3 = User::factory()->create();
        $this->user4 = User::factory()->create();

        $this->anime->reviewUsers()->attach($this->user1->id, [
            'score' => 0,
            'watch' => true,
        ]);
        $this->anime->reviewUsers()->attach($this->user2->id, [
            'one_word_comment' => 'excellent',
            'will_watch' => true,
            'spoiler' => true,
        ]);
        $this->anime->reviewUsers()->attach($this->user3->id, [
            'score' => 100,
            'one_word_comment' => 'not sad',
            'will_watch' => true,
            'watch' => true,
            'spoiler' => true,
        ]);
    }

    /**
     * アニメページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testAnimeView()
    {
        $response = $this->get("/anime/{$this->anime->id}");
        $response->assertStatus(200);
    }

    /**
     * アニメページのアニメ情報の表示のテスト
     *
     * @test
     * @return void
     */
    public function testAnimeInformationView()
    {
        $response = $this->get("/anime/{$this->anime->id}");
        $response->assertSeeInOrder([
            'https://public_url',
            '霊剣山 叡智への資格',
            'company',
            '2022年冬クール',
            'twitterId',
            'hashTag',
            70,
            76,
            256,
            100,
            0,
        ]);
    }

    /**
     * アニメページの声優情報の表示のテスト
     *
     * @test
     * @return void
     */
    public function testAnimeCastsView()
    {
        $response = $this->get("/anime/{$this->anime->id}");
        $response->assertSeeInOrder([$this->cast1->name, $this->cast2->name]);
    }

    /**
     * アニメページのレビュー情報の表示のテスト
     *
     * @test
     * @return void
     */
    public function testAnimeReviewsView()
    {
        $response = $this->get("/anime/{$this->anime->id}");
        $response->assertSeeInOrder([
            'excellent',
            $this->user2->uid,
            '100点',
            'not sad',
            $this->user3->uid,
        ]);
        // コメントしていないユーザーのレビュー情報の非表示を確認
        $response->assertDontSee($this->user1->uid);
    }

    /**
     * ゲスト時のアニメページのテスト
     *
     * @test
     * @return void
     */
    public function testGuestAnimeView()
    {
        $response = $this->get("/anime/{$this->anime->id}");
        //ゲスト時の表示確認
        $response->assertSee('ログインしてこのアニメに得点やコメントを登録する');
        $response->assertDontSee('つけた得点');
    }

    /**
     * 得点をつけたユーザーのログイン時のアニメページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testUser1LoginAnimeView()
    {
        $this->actingAs($this->user1);
        $response = $this->get("/anime/{$this->anime->id}");
        $response->assertDontSee('ログインしてこのアニメに得点やコメントを登録する');
        $response->assertSee('つけた得点');
    }

    /**
     * 得点をつけていないユーザーのログイン時のアニメページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testUser2LoginAnimeView()
    {
        $this->actingAs($this->user2);
        $response = $this->get("/anime/{$this->anime->id}");
        $response->assertDontSee('つけた得点');
    }

    /**
     * 存在しないアニメページにアクセスしたときのテスト
     *
     * @test
     * @return void
     */
    public function testNotExistAnimeView()
    {
        $response = $this->get('/anime/3333333333333333333333333');
        $response->assertStatus(404);
    }

    /**
     * ゲスト時のアニメ得点ページのリダイレクトのテスト
     *
     * @test
     * @return void
     */
    public function testGuestScoreAnimeView()
    {
        $response = $this->get("/anime/{$this->anime->id}/score");
        $response->assertRedirect('/login');
    }

    /**
     * ログイン時のアニメ得点ページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testUser1LoginScoreAnimeView()
    {
        $this->actingAs($this->user1);
        $response = $this->get("/anime/{$this->anime->id}/score");
        $response->assertStatus(200);
    }

    /**
     * レビューを入力していないユーザーのアニメ得点入力のテスト
     *
     * @test
     * @return void
     */
    public function testUser4ScoreAnimePost()
    {
        $this->actingAs($this->user4);
        $response = $this->post("/anime/{$this->anime->id}/score", [
            'score' => '35',
            'one_word_comment' => 'exellent',
            'watch' => true,
            'will_watch' => true,
            'spoiler' => true,
        ]);
        $this->assertDatabaseHas('user_reviews', [
            'anime_id' => $this->anime->id,
            'user_id' => $this->user4->id,
            'score' => 35,
            'one_word_comment' => 'exellent',
            'watch' => true,
            'will_watch' => true,
            'spoiler' => true,
        ]);
        $this->assertDatabaseHas('animes', [
            'id' => $this->anime->id,
            'median' => 35,
            'average' => 45,
            'count' => 4,
            'max' => 100,
            'min' => 0,
        ]);
        $response->assertRedirect("/anime/{$this->anime->id}");
        $this->get('/anime/1')->assertSee('入力が完了しました。');
    }

    /**
     * レビューをすべて入力済みのユーザーのアニメ得点null入力のテスト
     *
     * @test
     * @return void
     */
    public function testUser3ScoreAnimePost()
    {
        $this->actingAs($this->user3);
        $response = $this->post("/anime/{$this->anime->id}/score", [
            'score' => '',
            'one_word_comment' => '',
            'watch' => false,
            'will_watch' => false,
            'spoiler' => false,
        ]);
        $this->assertDatabaseHas('user_reviews', [
            'anime_id' => $this->anime->id,
            'user_id' => $this->user3->id,
            'score' => null,
            'one_word_comment' => null,
            'watch' => false,
            'will_watch' => false,
            'spoiler' => false,
        ]);
    }
}
