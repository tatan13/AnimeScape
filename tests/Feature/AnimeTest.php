<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Anime;
use App\Models\Cast;
use App\Models\Company;
use App\Models\Tag;
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
    private Tag $tag;
    private Tag $tag1;
    private User $user1;
    private User $user2;
    private User $user3;
    private User $user4;
    private User $user5;
    private User $user6;
    private User $user7;

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
            'before_median' => 70,
            'before_average' => 76,
            'before_count' => 256,
            'before_stdev' => 31,
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
        $this->user5 = User::factory()->create();
        $this->user6 = User::factory()->create();
        $this->user7 = User::factory()->create();

        $this->anime->reviewUsers()->attach($this->user1->id, [
            'score' => 0,
            'watch' => true,
            'before_score' => 0,
        ]);
        $this->anime->reviewUsers()->attach($this->user2->id, [
            'one_word_comment' => 'excellent',
            'will_watch' => 1,
            'spoiler' => true,
            'before_comment' => 'excellent',
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
            'before_score' => 100,
            'before_comment' => 'not sad',
        ]);
        $this->anime3->reviewUsers()->attach($this->user1->id, [
            'score' => 100,
            'one_word_comment' => 'not sad',
            'long_word_comment' => 'not long',
            'will_watch' => 1,
            'watch' => true,
            'spoiler' => true,
            'number_of_interesting_episode' => 12,
            'before_score' => 100,
            'before_comment' => 'not sad',
        ]);

        $this->tag = Tag::factory()->create();
        $this->tag1 = Tag::factory()->create();
        $this->anime->tags()->attach($this->tag->id, [
            'user_id' => $this->user4->id,
            'score' => 0,
            'comment' => 'this is tag comment',
        ]);
        $this->anime->tags()->attach($this->tag->id, [
            'user_id' => $this->user5->id,
            'score' => 30,
        ]);
        $this->anime->tags()->attach($this->tag->id, [
            'user_id' => $this->user6->id,
            'score' => 60,
        ]);
        $this->anime->tags()->attach($this->tag->id, [
            'user_id' => $this->user7->id,
            'score' => 100,
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
     * アニメページのタグ情報の表示のテスト
     *
     * @test
     * @return void
     */
    public function testAnimeTagInformationView()
    {
        $response = $this->get(route('anime.show', ['anime_id' => $this->anime->id]));
        $response->assertSeeInOrder([
            $this->tag->name,
            '4件',
            '中央値45点',
            'this is tag comment',
            $this->user4->name,
        ]);
        $response->assertDontSee($this->user5->name);
    }

    /**
     * ゲスト時のタグレビューページのリダイレクトのテスト
     *
     * @test
     * @return void
     */
    public function testGuestAnimeTagReviewView()
    {
        $response = $this->get(route('tag_review.show', ['anime_id' => $this->anime->id]));
        $response->assertRedirect(route('login'));
    }

    /**
     * ログイン時のタグレビューページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testUser4LoginTagReviewView()
    {
        $this->actingAs($this->user4);
        $response = $this->get(route('tag_review.show', ['anime_id' => $this->anime->id]));
        $response->assertStatus(200);
    }

    /**
     * ログイン時のタグレビューページの表示の異常値テスト
     *
     * @test
     * @return void
     */
    public function testUser4LoginNotExistTagReviewView()
    {
        $this->actingAs($this->user4);
        $response = $this->get(route('tag_review.show', ['anime_id' => 333333333333333333333]));
        $response->assertStatus(404);
    }

    /**
     * ユーザーのタグレビュー変更なしのテスト
     *
     * @test
     * @return void
     */
    public function testUser1TagReviewNoChangePost()
    {
        $this->actingAs($this->user4);
        $response = $this->post(route('tag_review.post', ['anime_id' => $this->anime->id,
            'tag_review_id[1]' => 1,
            'modify_type[1]' => 'no_change',
            'name[1]' => $this->tag->name,
            'score[1]' => 35,
            'comment[1]' => 'no change'
        ]));
        $this->assertDatabaseHas('tag_reviews', [
            'anime_id' => $this->anime->id,
            'user_id' => $this->user4->id,
            'tag_id' => $this->tag->id,
            'score' => 0,
            'comment' => 'this is tag comment',
        ]);
        $response->assertRedirect(route('anime.show', ['anime_id' => $this->anime->id]));
        $this->get(route('anime.show', ['anime_id' => $this->anime->id]))->assertSee('入力が完了しました。');
    }

    /**
     * ユーザーのタグレビュー削除のテスト
     *
     * @test
     * @return void
     */
    public function testUser4TagReviewDeletePost()
    {
        $this->actingAs($this->user4);
        $response = $this->post(route('tag_review.post', ['anime_id' => $this->anime->id,
            'tag_review_id[1]' => 1,
            'modify_type[1]' => 'delete',
            'name[1]' => $this->tag->name,
            'score[1]' => 35,
            'comment[1]' => 'delete'
        ]));
        $this->assertDatabaseMissing('tag_reviews', [
            'anime_id' => $this->anime->id,
            'user_id' => $this->user4->id,
            'tag_id' => $this->tag->id,
            'score' => 0,
            'comment' => 'this is tag comment',
        ]);
        $response->assertRedirect(route('anime.show', ['anime_id' => $this->anime->id]));
        $this->get(route('anime.show', ['anime_id' => $this->anime->id]))->assertSee('入力が完了しました。');
    }

    /**
     * ユーザーのタグレビュー変更のテスト
     *
     * @test
     * @return void
     */
    public function testUser4TagReviewChangePost()
    {
        $this->actingAs($this->user4);
        $response = $this->post(route('tag_review.post', [
            'anime_id' => $this->anime->id,
            'tag_review_id[1]' => 1,
            'modify_type[1]' => 'change',
            'name[1]' => $this->tag->name,
            'score[1]' => 35,
            'comment[1]' => 'change'
        ]));
        $this->assertDatabaseHas('tag_reviews', [
            'anime_id' => $this->anime->id,
            'user_id' => $this->user4->id,
            'tag_id' => $this->tag->id,
            'score' => 35,
            'comment' => 'change',
        ]);
        $response->assertRedirect(route('anime.show', ['anime_id' => $this->anime->id]));
        $this->get(route('anime.show', ['anime_id' => $this->anime->id]))->assertSee('入力が完了しました。');
    }

    /**
     * ユーザーのタグレビュー追加タグ作成のテスト
     *
     * @test
     * @return void
     */
    public function testUser4TagReviewAddTagCreatePost()
    {
        $this->actingAs($this->user4);
        $response = $this->post(route('tag_review.post', [
            'anime_id' => $this->anime->id,
            'tag_review_id[1]' => 1,
            'modify_type[1]' => 'no_change',
            'name[1]' => $this->tag->name,
            'score[1]' => 35,
            'comment[1]' => 'no change',
            'modify_type[2]' => 'add',
            'name[2]' => 'tag_name',
            'tag_group_id[2]' => 3,
            'score[2]' => 35,
            'comment[2]' => 'add'
        ]));
        $this->assertDatabaseHas('tags', [
            'name' => 'tag_name',
            'id' => 3,
            'tag_group_id' => 3,
        ]);
        $this->assertDatabaseHas('tag_reviews', [
            'anime_id' => $this->anime->id,
            'user_id' => $this->user4->id,
            'tag_id' => 3,
            'score' => 35,
            'comment' => 'add',
        ]);
        $response->assertRedirect(route('anime.show', ['anime_id' => $this->anime->id]));
        $this->get(route('anime.show', ['anime_id' => $this->anime->id]))->assertSee('入力が完了しました。');
    }

    /**
     * ユーザーのタグレビュー追加のテスト
     *
     * @test
     * @return void
     */
    public function testUser4TagReviewAddTagNoCreatePost()
    {
        $this->actingAs($this->user4);
        $response = $this->post(route('tag_review.post', [
            'anime_id' => $this->anime->id,
            'tag_review_id[1]' => 1,
            'modify_type[1]' => 'no_change',
            'name[1]' => $this->tag->name,
            'score[1]' => 35,
            'comment[1]' => 'no change',
            'modify_type[2]' => 'add',
            'name[2]' => $this->tag1->name,
            'tag_group_id[2]' => 3,
            'score[2]' => 35,
            'comment[2]' => 'add'
        ]));
        $this->assertDatabaseHas('tag_reviews', [
            'anime_id' => $this->anime->id,
            'user_id' => $this->user4->id,
            'tag_id' => $this->tag1->id,
            'score' => 35,
            'comment' => 'add',
        ]);
        $response->assertRedirect(route('anime.show', ['anime_id' => $this->anime->id]));
        $this->get(route('anime.show', ['anime_id' => $this->anime->id]))->assertSee('入力が完了しました。');
    }

    /**
     * ユーザーの同一タグレビュー追加のテスト
     *
     * @test
     * @return void
     */
    public function testUser4ExistTagReviewAddTagPost()
    {
        $this->actingAs($this->user4);
        $response = $this->post(route('tag_review.post', [
            'anime_id' => $this->anime->id,
            'tag_review_id[1]' => 1,
            'modify_type[1]' => 'no_change',
            'name[1]' => $this->tag->name,
            'score[1]' => 35,
            'comment[1]' => 'no change',
            'modify_type[2]' => 'add',
            'name[2]' => $this->tag->name,
            'tag_group_id[2]' => 3,
            'score[2]' => 30,
            'comment[2]' => 'add'
        ]));
        $this->assertDatabaseMissing('tag_reviews', [
            'anime_id' => $this->anime->id,
            'user_id' => $this->user4->id,
            'tag_id' => $this->tag->id,
            'score' => 35,
            'comment' => 'add',
        ]);
        $response->assertRedirect(route('anime.show', ['anime_id' => $this->anime->id]));
        $this->get(route('anime.show', ['anime_id' => $this->anime->id]))->assertSee('入力が完了しました。');
    }

    /**
     * 存在しないアニメのタグレビュー入力のテスト
     *
     * @test
     * @return void
     */
    public function testUser4NotExistTagReviewPost()
    {
        $this->actingAs($this->user4);
        $response = $this->post(route('tag_review.post', [
            'anime_id' => 333333333333333333333,
            'tag_review_id[1]' => 1,
            'modify_type[1]' => 'no_change',
            'name[1]' => $this->tag->name,
            'score[1]' => 35,
            'comment[1]' => 'no change',
            'modify_type[2]' => 'add',
            'name[2]' => $this->tag1->name,
            'tag_group_id[2]' => 3,
            'score[2]' => 35,
            'comment[2]' => 'add'
        ]));
        $response->assertStatus(404);
    }

    /**
     * アニメページのレビュー情報の表示のテスト
     *
     * @test
     * @return void
     */
    public function testAnimeCommentView()
    {
        $response = $this->get(route('anime.show', ['anime_id' => $this->anime->id]));
        $response->assertSeeInOrder([
            '100点',
            'not sad',
            '長文感想',
            'ネタバレ注意',
            $this->user3->name,
            'excellent',
            $this->user2->name,
        ]);
        // コメントしていないユーザーのレビュー情報の非表示を確認
        $response->assertDontSee($this->user1->name);
    }

    /**
     * アニメページの視聴完了前統計情報の表示のテスト
     *
     * @test
     * @return void
     */
    public function testAnimeBeforeStatisticsView()
    {
        $response = $this->get(route('anime.show', ['anime_id' => $this->anime->id]));
        $response->assertSeeInOrder([
            '視聴完了前統計情報',
            $this->anime->before_median,
            $this->anime->before_average,
            $this->anime->before_count,
            $this->anime->before_stdev,
        ]);
    }

    /**
     * アニメページの視聴完了前コメントの表示のテスト
     *
     * @test
     * @return void
     */
    public function testAnimeBeforeCommentView()
    {
        $response = $this->get(route('anime.show', ['anime_id' => $this->anime->id]));
        $response->assertSeeInOrder([
            '視聴完了前コメント（新着順）',
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
            'before_score' => 35,
            'before_comment' => 'exellent',
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
            'before_score' => 35,
            'before_comment' => 'exellent',
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
            'before_median' => 35,
            'before_average' => 45,
            'before_count' => 3,
            'before_stdev' => 41.43267631552,
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
            'before_score' => '',
            'before_comment' => '',
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
            'before_score' => null,
            'before_comment' => null,
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
            'before_score' => '',
            'before_comment' => '',
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
            $this->anime3->title,
            'not sad',
            $this->anime->title,
            0,
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
            'before_score[1]' => 40,
            'before_comment[1]' => 'not sad',
            'anime_id[2]' => $this->anime1->id,
            'score[2]' => 35,
            'watch[2]' => 1,
            'will_watch[2]' => 1,
            'now_watch[2]' => 1,
            'give_up[2]' => 1,
            'number_of_interesting_episode[2]' => 1,
            'one_word_comment[2]' => 'not sad',
            'before_score[2]' => 35,
            'before_comment[2]' => 'not sad',
            'anime_id[3]' => $this->anime2->id,
            'score[3]' => '',
            'watch[3]' => 0,
            'will_watch[3]' => 0,
            'now_watch[3]' => 0,
            'give_up[3]' => 0,
            'number_of_interesting_episode[3]' => '',
            'one_word_comment[3]' => '',
            'before_score[3]' => '',
            'before_comment[3]' => '',
            'anime_id[4]' => $this->anime3->id,
            'score[4]' => '',
            'watch[4]' => 0,
            'will_watch[4]' => 0,
            'now_watch[4]' => 0,
            'give_up[4]' => 0,
            'number_of_interesting_episode[4]' => '',
            'one_word_comment[4]' => '',
            'before_score[4]' => '',
            'before_comment[4]' => '',
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
            'before_score' => 40,
            'before_comment' => 'not sad',
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
            'before_score' => 35,
            'before_comment' => 'not sad',
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
            'before_score' => null,
            'before_comment' => null,
        ]);
        $this->assertDatabaseHas('animes', [
            'id' => $this->anime->id,
            'median' => 70,
            'average' => 70,
            'count' => 2,
            'max' => 100,
            'min' => 40,
            'before_median' => 70,
            'before_average' => 70,
            'before_count' => 2,
        ]);
        $this->assertDatabaseHas('animes', [
            'id' => $this->anime1->id,
            'median' => 35,
            'average' => 35,
            'count' => 1,
            'max' => 35,
            'min' => 35,
            'before_median' => 35,
            'before_average' => 35,
            'before_count' => 1,
        ]);
        $this->assertDatabaseHas('animes', [
            'id' => $this->anime3->id,
            'median' => null,
            'average' => null,
            'count' => 0,
            'max' => null,
            'min' => null,
            'before_median' => null,
            'before_average' => null,
            'before_count' => 0,
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
            'before_score[1]' => 40,
            'before_comment[1]' => 'not sad',
            'anime_id[2]' => $this->anime1->id,
            'score[2]' => 35,
            'watch[2]' => 1,
            'will_watch[2]' => 1,
            'spoiler[2]' => 1,
            'one_word_comment[2]' => 'not sad',
            'before_score[2]' => 35,
            'before_comment[2]' => 'not sad',
            'anime_id[3]' => $this->anime2->id,
            'score[3]' => '',
            'watch[3]' => 0,
            'will_watch[3]' => 0,
            'spoiler[3]' => 0,
            'one_word_comment[3]' => '',
            'before_score[3]' => '',
            'before_comment[3]' => '',
            'anime_id[4]' => $this->anime3->id,
            'score[4]' => '',
            'watch[4]' => 0,
            'will_watch[4]' => 0,
            'spoiler[4]' => 0,
            'one_word_comment[4]' => '',
            'before_score[4]' => '',
            'before_comment[4]' => '',
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

    /**
     * アニメの視聴完了前得点を付けたユーザーリストの表示のテスト
     *
     * @test
     * @return void
     */
    public function testAnimeBeforeScoreListView()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('anime_before_score_list.show', ['anime_id' => $this->anime->id]));

        $response->assertStatus(200);
        $response->assertSeeInOrder([
            '視聴完了前得点を付けたユーザーリスト',
            $this->user1->name,
            $this->user3->name,
        ]);
    }
}
