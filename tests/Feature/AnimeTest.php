<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Anime;
use App\Models\Cast;
use App\Models\Company;
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
    private Company $company;
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
            'number_of_episode' => 13,
            'median' => 70,
            'average' => 76,
            'count' => 256,
            'stdev' => 31,
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

        $this->company = Company::factory()->create();
        $this->anime->companies()->attach($this->company->id);

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
            'will_watch' => 1,
            'spoiler' => true,
        ]);
        $this->anime->reviewUsers()->attach($this->user3->id, [
            'score' => 100,
            'one_word_comment' => 'not sad',
            'long_word_comment' => 'not long',
            'will_watch' => 1,
            'watch' => true,
            'spoiler' => true,
            'now_watch' => true,
            'give_up' => true,
            'number_of_interesting_episode' => 15,
        ]);
        $this->anime3->reviewUsers()->attach($this->user1->id, [
            'score' => 100,
            'one_word_comment' => 'not sad',
            'long_word_comment' => 'not long',
            'will_watch' => 1,
            'watch' => true,
            'spoiler' => true,
            'number_of_interesting_episode' => 12,
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
        $response = $this->get(route('anime.show', ['anime_id' => $this->anime->id]));
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
        $response = $this->get(route('anime.show', ['anime_id' => $this->anime->id]));
        $response->assertSeeInOrder([
            $this->anime->public_url,
            $this->anime->title,
            $this->anime->companies[0]->name,
            $this->anime->year,
            $this->anime->coor_label,
            $this->anime->number_of_episode,
            $this->anime->media_category_label,
            $this->anime->twitter,
            $this->anime->hash_tag,
            $this->anime->city_name,
            $this->anime->median,
            $this->anime->average,
            $this->anime->count,
            $this->anime->stdev,
            $this->anime->max,
            $this->anime->min,
            $this->anime->number_of_interesting_episode,
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
        $response = $this->get(route('anime.show', ['anime_id' => $this->anime->id]));
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
        $response = $this->get(route('anime.show', ['anime_id' => $this->anime->id]));
        $response->assertSeeInOrder([
            'excellent',
            $this->user2->name,
            '100点',
            'not sad',
            '長文感想',
            'ネタバレ注意',
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
        $response = $this->get(route('anime.show', ['anime_id' => $this->anime->id]));
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
        $response = $this->get(route('anime.show', ['anime_id' => $this->anime->id]));
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
        $response = $this->get(route('anime.show', ['anime_id' => $this->anime->id]));
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
        $response = $this->get(route('anime.show', ['anime_id' => 3333333333333333333]));
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
        $response = $this->get(route('anime_review.show', ['anime_id' => $this->anime->id]));
        $response->assertRedirect(route('login'));
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
        $response = $this->get(route('anime_review.show', ['anime_id' => $this->anime->id]));
        $response->assertStatus(200);
    }

    /**
     * ログイン時のアニメ得点ページの表示の異常値テスト
     *
     * @test
     * @return void
     */
    public function testUser1LoginNotExistAnimeReviewView()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('anime_review.show', ['anime_id' => 333333333333333333333]));
        $response->assertStatus(404);
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
        $response = $this->post(route('anime_review.post', ['anime_id' => $this->anime->id]), [
            'score' => 35,
            'one_word_comment' => 'exellent',
            'watch' => true,
            'will_watch' => 1,
            'now_watch' => true,
            'give_up' => true,
            'spoiler' => true,
            'number_of_interesting_episode' => 14,
        ]);
        $this->assertDatabaseHas('user_reviews', [
            'anime_id' => $this->anime->id,
            'user_id' => $this->user4->id,
            'score' => 35,
            'one_word_comment' => 'exellent',
            'watch' => true,
            'will_watch' => 1,
            'now_watch' => true,
            'give_up' => true,
            'spoiler' => true,
            'number_of_interesting_episode' => 14,
        ]);
        $this->assertDatabaseHas('animes', [
            'id' => $this->anime->id,
            'median' => 35,
            'average' => 45,
            'count' => 3,
            'stdev' => 41.43267631552,
            'max' => 100,
            'min' => 0,
            'number_of_interesting_episode' => 14.5,
        ]);
        $response->assertRedirect(route('anime.show', ['anime_id' => $this->anime->id]));
        $this->get(route('anime.show', ['anime_id' => $this->anime->id]))->assertSee('入力が完了しました。');
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
        $response = $this->post(route('anime_review.post', ['anime_id' => $this->anime->id]), [
            'score' => '',
            'one_word_comment' => '',
            'long_word_comment' => '',
            'watch' => false,
            'will_watch' => 0,
            'now_watch' => false,
            'give_up' => false,
            'spoiler' => false,
            'number_of_interesting_episode' => '',
        ]);
        $this->assertDatabaseHas('user_reviews', [
            'anime_id' => $this->anime->id,
            'user_id' => $this->user3->id,
            'score' => null,
            'one_word_comment' => null,
            'long_word_comment' => null,
            'watch' => false,
            'will_watch' => 0,
            'now_watch' => false,
            'give_up' => false,
            'spoiler' => false,
            'number_of_interesting_episode' => null,
        ]);
    }

    /**
     * 存在しないアニメ得点入力のテスト
     *
     * @test
     * @return void
     */
    public function testUser1NotExistAnimeReviewPost()
    {
        $this->actingAs($this->user1);
        $response = $this->post(route('anime_review.post', ['anime_id' => 3333333333333333333333]), [
            'score' => '',
            'one_word_comment' => '',
            'watch' => false,
            'will_watch' => 0,
            'spoiler' => false,
        ]);
        $response->assertStatus(404);
    }

    /**
     * ゲストのアニメ得点一括入力ページリクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testGuestAnimeReviewListView()
    {
        $response = $this->get(route('anime_review_list.show', [
            'year' => 2022,
            'coor' => 1,
        ]));
        $response->assertRedirect(route('login'));
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
        $response = $this->get(route('anime_review_list.show', [
            'year' => 2022,
            'coor' => 1,
        ]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->anime->title,
            $this->anime->companies[0]->name,
            $this->anime->year,
            $this->anime->coor_label,
            0,
            $this->anime3->title,
            'not sad',
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
        $response = $this->post(route('anime_review_list.post', [
            'year' => 2022,
            'coor' => 1,
            'anime_id[1]' => $this->anime->id,
            'score[1]' => 40,
            'watch[1]' => 1,
            'will_watch[1]' => 1,
            'now_watch[1]' => 1,
            'give_up[1]' => 1,
            'number_of_interesting_episode[1]' => 1,
            'one_word_comment[1]' => 'not sad',
            'anime_id[2]' => $this->anime1->id,
            'score[2]' => 35,
            'watch[2]' => 1,
            'will_watch[2]' => 1,
            'now_watch[2]' => 1,
            'give_up[2]' => 1,
            'number_of_interesting_episode[2]' => 1,
            'one_word_comment[2]' => 'not sad',
            'anime_id[3]' => $this->anime2->id,
            'score[3]' => '',
            'watch[3]' => 0,
            'will_watch[3]' => 0,
            'now_watch[3]' => 0,
            'give_up[3]' => 0,
            'number_of_interesting_episode[3]' => '',
            'one_word_comment[3]' => '',
            'anime_id[4]' => $this->anime3->id,
            'score[4]' => '',
            'watch[4]' => 0,
            'will_watch[4]' => 0,
            'now_watch[4]' => 0,
            'give_up[4]' => 0,
            'number_of_interesting_episode[4]' => '',
            'one_word_comment[4]' => '',
        ]));
        $response->assertRedirect(route('anime_review_list.show', [
            'year' => 2022,
            'coor' => 1,
        ]));
        $this->get(route('anime_review_list.show', [
            'year' => 2022,
            'coor' => 1,
        ]))->assertSee('入力が完了しました。');
        $this->assertDatabaseHas('user_reviews', [
            'anime_id' => $this->anime->id,
            'user_id' => $this->user1->id,
            'score' => 40,
            'one_word_comment' => 'not sad',
            'watch' => true,
            'will_watch' => 1,
            'number_of_interesting_episode' => 1,
            'now_watch' => true,
            'give_up' => true,
        ]);
        $this->assertDatabaseHas('user_reviews', [
            'anime_id' => $this->anime1->id,
            'user_id' => $this->user1->id,
            'score' => 35,
            'one_word_comment' => 'not sad',
            'watch' => true,
            'will_watch' => 1,
            'number_of_interesting_episode' => 1,
            'now_watch' => true,
            'give_up' => true,
        ]);
        $this->assertDatabaseMissing('user_reviews', [
            'anime_id' => $this->anime2->id,
            'user_id' => $this->user1->id,
        ]);
        $this->assertDatabaseHas('user_reviews', [
            'anime_id' => $this->anime3->id,
            'user_id' => $this->user1->id,
            'score' => null,
            'one_word_comment' => null,
            'watch' => false,
            'will_watch' => 0,
            'number_of_interesting_episode' => null,
            'now_watch' => false,
            'give_up' => false,
        ]);
        $this->assertDatabaseHas('animes', [
            'id' => $this->anime->id,
            'median' => 70,
            'average' => 70,
            'count' => 2,
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
     * ログイン時のアニメ得点一括入力の異常値テスト
     *
     * @test
     * @return void
     */
    public function testUser1LoginNotExistAnimeReviewListPost()
    {
        $this->actingAs($this->user1);
        $response = $this->post(route('anime_review_list.post', [
            'year' => 2022,
            'coor' => 1,
            'anime_id[1]' => 33333333,
            'score[1]' => 40,
            'watch[1]' => 1,
            'will_watch[1]' => 1,
            'spoiler[1]' => 1,
            'one_word_comment[1]' => 'not sad',
            'anime_id[2]' => $this->anime1->id,
            'score[2]' => 35,
            'watch[2]' => 1,
            'will_watch[2]' => 1,
            'spoiler[2]' => 1,
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
        $response->assertStatus(404);
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
        $response = $this->get(route('anime.show', ['anime_id' => $this->anime->id]));
        $response->assertSee('このアニメを削除する');
    }

    /**
     * アニメの得点を付けたユーザーリストの表示のテスト
     *
     * @test
     * @return void
     */
    public function testAnimeScoreListView()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('anime_score_list.show', ['anime_id' => $this->anime->id]));

        $response->assertStatus(200);
        $response->assertSeeInOrder([
            '得点を付けたユーザーリスト',
            $this->user1->name,
            $this->user3->name,
        ]);
    }
}
