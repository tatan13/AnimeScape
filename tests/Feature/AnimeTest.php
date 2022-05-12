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
    private Anime $anime1;
    private Anime $anime2;
    private Anime $anime3;
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
        $this->anime1 = Anime::factory()->create();
        $this->anime2 = Anime::factory()->create();
        $this->anime3 = Anime::factory()->create();

        $this->cast1 = Cast::factory()->create();
        $this->cast2 = Cast::factory()->create();

        $this->anime->actCasts()->attach($this->cast1->id);
        $this->anime->actCasts()->attach($this->cast2->id);

        $this->user1 = User::factory()->create(['name' => 'root']);
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
        $this->anime3->reviewUsers()->attach($this->user1->id, [
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
            $this->user2->name,
            '100点',
            'not sad',
            $this->user3->name,
        ]);
        // コメントしていないユーザーのレビュー情報の非表示を確認
        $response->assertDontSee($this->user1->name);
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
        $response->assertSee('ログインしてこのアニメに得点やコメントを登録する');
        $response->assertDontSee('つけた得点');
        $response->assertDontSee('このアニメを削除する');
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
        $response->assertStatus(200);
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
        $response->assertDontSee('このアニメを削除する');
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
    public function testGuestAnimeReviewView()
    {
        $response = $this->get("/anime/{$this->anime->id}/review");
        $response->assertRedirect('/login');
    }

    /**
     * ログイン時のアニメ得点ページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testUser1LoginAnimeReviewView()
    {
        $this->actingAs($this->user1);
        $response = $this->get("/anime/{$this->anime->id}/review");
        $response->assertStatus(200);
    }

    /**
     * レビューを入力していないユーザーのアニメ得点入力のテスト
     *
     * @test
     * @return void
     */
    public function testUser4AnimeReviewPost()
    {
        $this->actingAs($this->user4);
        $response = $this->post("/anime/{$this->anime->id}/review", [
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
    public function testUser3AnimeReviewPost()
    {
        $this->actingAs($this->user3);
        $response = $this->post("/anime/{$this->anime->id}/review", [
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

    /**
     * ゲストのアニメ得点一括入力ページリクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testGuestAnimeReviewListView()
    {
        $response = $this->get(route("anime_review_list.show", [
            'year' => 2022,
            'coor' => 1,
        ]));
        $response->assertRedirect('/login');
    }

    /**
     * ログイン時のアニメ得点一括入力ページリクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testUser1LoginAnimeReviewListView()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route("anime_review_list.show", [
            'year' => 2022,
            'coor' => 1,
        ]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->anime->title,
            $this->anime1->title,
            $this->anime2->title,
        ]);
    }

    /**
     * ログイン時のアニメ得点一括入力のテスト
     *
     * @test
     * @return void
     */
    public function testUser1LoginAnimeReviewListPost()
    {
        $this->actingAs($this->user1);
        $response = $this->post(route("anime_review_list.post", [
            "year" => "2022",
            "coor" => "1",
            'anime_id[1]' => $this->anime->id,
            'score[1]' => 40,
            'watch[1]' => true,
            'will_watch[1]' => true,
            'spoiler[1]' => true,
            'one_word_comment[1]' => 'not sad',
            'anime_id[2]' => $this->anime1->id,
            'score[2]' => 35,
            'watch[2]' => true,
            'will_watch[2]' => true,
            'spoiler[2]' => true,
            'one_word_comment[2]' => 'not sad',
            'anime_id[3]' => $this->anime2->id,
            'score[3]' => '',
            'watch[3]' => 0,
            'will_watch[3]' => 0,
            'spoiler[3]' => 0,
            'one_word_comment[3]' => '',
            'anime_id[4]' => $this->anime3->id,
            'score[4]' => '',
            'watch[4]' => 0,
            'will_watch[4]' => 0,
            'spoiler[4]' => 0,
            'one_word_comment[4]' => '',
        ]));
        $response->assertRedirect('/anime_review_list?year=2022&coor=1');
        $this->get('/anime_review_list?year=2022&coor=1')->assertSee('入力が完了しました。');
        $this->assertDatabaseHas('user_reviews', [
            'anime_id' => $this->anime->id,
            'user_id' => $this->user1->id,
            'score' => 40,
            'one_word_comment' => 'not sad',
            'watch' => true,
            'will_watch' => true,
            'spoiler' => true,
        ]);
        $this->assertDatabaseHas('user_reviews', [
            'anime_id' => $this->anime1->id,
            'user_id' => $this->user1->id,
            'score' => 35,
            'one_word_comment' => 'not sad',
            'watch' => true,
            'will_watch' => true,
            'spoiler' => true,
        ]);
        $this->assertDatabaseMissing('user_reviews', [
            'anime_id' => $this->anime2->id,
            'user_id' => $this->user1->id,
        ]);
        $this->assertDatabaseMissing('user_reviews', [
            'anime_id' => $this->anime3->id,
            'user_id' => $this->user1->id,
        ]);
        $this->assertDatabaseHas('animes', [
            'id' => $this->anime->id,
            'median' => 70,
            'average' => 70,
            'count' => 3,
            'max' => 100,
            'min' => 40,
        ]);
        $this->assertDatabaseHas('animes', [
            'id' => $this->anime1->id,
            'median' => 35,
            'average' => 35,
            'count' => 1,
            'max' => 35,
            'min' => 35,
        ]);
        $this->assertDatabaseHas('animes', [
            'id' => $this->anime3->id,
            'median' => null,
            'average' => null,
            'count' => 0,
            'max' => null,
            'min' => null,
        ]);
    }

    /**
     * ルートログイン時のアニメの削除リンクの表示のテスト
     *
     * @test
     * @return void
     */
    public function testRoot1LoginAnimeDeleteView()
    {
        $this->actingAs($this->user1);
        $response = $this->get("/anime/{$this->anime->id}");
        $response->assertSee('このアニメを削除する');
    }

    /**
     * ルートログイン時のアニメ削除のテスト
     *
     * @test
     * @return void
     */
    public function testRootLoginAnimeDelete()
    {
        $this->actingAs($this->user1);
        $response = $this->get("/anime/{$this->anime->id}/delete");
        $response->assertRedirect('/');
        $this->assertDatabaseMissing('animes', [
            'id' => $this->anime->id,
        ]);
    }

    /**
     * ゲスト時のアニメ削除のテスト
     *
     * @test
     * @return void
     */
    public function testGuestAnimeDelete()
    {
        $response = $this->get("/anime/{$this->anime->id}/delete");
        $response->assertStatus(403);
    }

    /**
     * ユーザーログイン時のアニメ削除のテスト
     *
     * @test
     * @return void
     */
    public function testUserAnimeDelete()
    {
        $this->actingAs($this->user2);
        $response = $this->get("/anime/{$this->anime->id}/delete");
        $response->assertStatus(403);
    }
}
