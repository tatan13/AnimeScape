<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\ModifyAnime;
use App\Models\ModifyOccupation;
use App\Models\ModifyCast;
use App\Models\DeleteAnime;
use App\Models\AddAnime;
use App\Models\Anime;
use App\Models\Cast;
use App\Models\User;
use Tests\TestCase;

class ModifyTest extends TestCase
{
    use RefreshDatabase;

    private Anime $anime;
    private Anime $anime1;
    private ModifyAnime $modifyAnime;
    private ModifyAnime $modifyAnime1;
    private ModifyCast $modifyCast1;
    private ModifyCast $modifyCast2;
    private DeleteAnime $deleteAnime1;
    private AddAnime $addAnime;
    private Cast $cast1;
    private Cast $cast2;
    private User $user1;
    private User $user2;

    protected function setUp(): void
    {
        parent::setUp();
        $this->anime = Anime::factory()->create();
        $this->anime1 = Anime::factory()->create();
        $this->modifyAnime = ModifyAnime::factory()->create(['anime_id' => $this->anime->id]);
        $this->modifyAnime1 = ModifyAnime::factory()->create([
            'anime_id' => $this->anime1->id,
            'title' => 'modify_title2',
            'title_short' => 'modify_title_short2',
        ]);
        $this->cast1 = Cast::factory()->create();
        $this->cast2 = Cast::factory()->create();
        $this->modifyCast1 = ModifyCast::factory()->create(['cast_id' => $this->cast1->id]);
        $this->modifyCast2 = ModifyCast::factory()->create([
            'cast_id' => $this->cast1->id,
            'name' => 'modify_name1',
        ]);
        $this->anime->modifyOccupations()->create(['cast_name' => $this->cast1->name]);
        $this->anime->modifyOccupations()->create(['cast_name' => 'modify_cast1']);
        $this->anime1->modifyOccupations()->create(['cast_name' => 'modify_cast1']);
        $this->anime1->modifyOccupations()->create(['cast_name' => 'modify_cast2']);
        $this->anime->actCasts()->attach($this->cast1->id);
        $this->anime->actCasts()->attach($this->cast2->id);

        $this->deleteAnime1 = DeleteAnime::create(['anime_id' => $this->anime1->id, 'remark' => 'remark1']);

        $this->addAnime = AddAnime::create([
            'title' => 'add_title',
            'year' => 2040,
            'coor' => 4,
            'public_url' => 'https://add_public_url',
            'twitter' => 'add_twitterId',
            'hash_tag' => 'add_hashTag',
            'company' => 'add_company',
        ]);

        $this->user1 = User::factory()->create(['name' => 'root']);
        $this->user2 = User::factory()->create();
    }

    /**
     * アニメの基本情報変更申請ページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testModifyAnimeView()
    {
        $response = $this->get(route('modify_anime_request.show', [
            'anime_id' => $this->anime->id,
        ]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->anime->title,
            $this->anime->year,
            $this->anime->coor_label,
            $this->anime->public_url,
            $this->anime->twitter,
            $this->anime->hash_tag,
            $this->anime->company,
        ]);
    }

    /**
     * アニメの基本情報変更申請ページの表示の異常値テスト
     *
     * @test
     * @return void
     */
    public function testNotExistModifyAnimeView()
    {
        $response = $this->get(route('modify_anime_request.show', [
            'anime_id' => 333333333333333333333333333,
        ]));
        $response->assertStatus(404);
    }

    /**
     * アニメの基本情報変更申請のテスト
     *
     * @test
     * @return void
     */
    public function testModifyAnimePost()
    {
        $response = $this->post(route('modify_anime_request.post', ['anime_id' => $this->anime->id]), [
            'title' => 'modify_title',
            'year' => 2040,
            'coor' => 4,
            'public_url' => 'https://modify_public_url',
            'twitter' => 'modify_twitterId',
            'hash_tag' => 'modify_hashTag',
            'company' => 'modify_company',
        ]);
        $response->assertRedirect(route('modify_anime_request.show', [
            'anime_id' => $this->anime->id,
        ]));
        $this->assertDatabaseHas('modify_animes', [
            'id' => 3,
            'anime_id' => $this->anime->id,
            'title' => 'modify_title',
            'year' => 2040,
            'coor' => 4,
            'public_url' => 'https://modify_public_url',
            'twitter' => 'modify_twitterId',
            'hash_tag' => 'modify_hashTag',
            'company' => 'modify_company',
        ]);
    }

    /**
     * アニメの基本情報変更申請の異常値テスト
     *
     * @test
     * @return void
     */
    public function testNotExistModifyAnimePost()
    {
        $response = $this->post(route('modify_anime_request.post', ['anime_id' => 333333333333333333333333333]), [
            'title' => 'modify_title',
            'year' => 2040,
            'coor' => 4,
            'public_url' => 'https://modify_public_url',
            'twitter' => 'modify_twitterId',
            'hash_tag' => 'modify_hashTag',
            'company' => 'modify_company',
        ]);
        $response->assertStatus(404);
    }

    /**
     * アニメの出演声優情報修正申請ページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testModifyOccupationsView()
    {
        $response = $this->get((route('modify_occupations_request.show', ['anime_id' => $this->anime->id])));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->cast1->name,
            $this->cast2->name,
        ]);
    }

    /**
     * アニメの出演声優情報修正申請ページの表示の異常値テスト
     *
     * @test
     * @return void
     */
    public function testNotExistModifyOccupationsView()
    {
        $response = $this->get((route('modify_occupations_request.show', ['anime_id' => 3333333333333333333333333])));
        $response->assertStatus(404);
    }

    /**
     * アニメの出演声優情報修正申請のテスト
     *
     * @test
     * @return void
     */
    public function testModifyOccupationsPost()
    {
        $response = $this->post(route('modify_occupations_request.post', ['anime_id' => $this->anime->id,]), [
            'cast_name_0' => $this->cast1->name,
            'cast_name_1' => 'modify_cast2',
            'cast_name_add_2' => null,
        ]);
        $this->assertDatabaseHas('modify_occupations', [
            'anime_id' => $this->anime->id,
            'cast_name' => $this->cast1->name,
        ]);
        $this->assertDatabaseHas('modify_occupations', [
            'anime_id' => $this->anime->id,
            'cast_name' => 'modify_cast1',
        ]);
        $this->assertDatabaseHas('modify_occupations', [
            'anime_id' => $this->anime->id,
            'cast_name' => 'modify_cast2',
        ]);
        $this->assertDatabaseMissing('modify_occupations', [
            'anime_id' => $this->anime->id,
            'cast_name' => null,
        ]);
        $this->assertDatabaseMissing('modify_occupations', [
            'anime_id' => $this->anime->id,
            'cast_name' => $this->cast2->name,
        ]);
    }

    /**
     * アニメの出演声優情報修正申請の異常値テスト
     *
     * @test
     * @return void
     */
    public function testNotExistModifyOccupationsPost()
    {
        $response = $this->post(route('modify_occupations_request.post', ['anime_id' => 33333333333333333333]), [
            'cast_name_0' => $this->cast1->name,
            'cast_name_1' => 'modify_cast2',
            'cast_name_add_2' => null,
        ]);
        $response->assertStatus(404);
    }

    /**
     * 声優情報修正申請ページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testModifyCastRequestView()
    {
        $response = $this->get(route('modify_cast_request.show', ['cast_id' => $this->cast1->id]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->cast1->name,
            $this->cast1->furigana,
            $this->cast1->sex_label,
            $this->cast1->office,
            $this->cast1->url,
            $this->cast1->twitter,
            $this->cast1->blog,
        ]);
    }

    /**
     * 声優情報修正申請ページの表示の異常値テスト
     *
     * @test
     * @return void
     */
    public function testNotExistModifyCastRequestView()
    {
        $response = $this->get(route('modify_cast_request.show', ['cast_id' => 333333333333333333333333333]));
        $response->assertStatus(404);
    }

    /**
     * 声優情報修正申請のテスト
     *
     * @test
     * @return void
     */
    public function testModifyCastRequestPost()
    {
        $response = $this->post(route('modify_cast_request.post', ['cast_id' => $this->cast1->id,]), [
            'name' => 'modify_name',
            'furigana' => 'modify_furigana',
            'sex' => 2,
            'office' => 'modify_office',
            'url' => 'modify_url',
            'twitter' => 'modify_twitter',
            'blog' => 'modify_blog',
        ]);
        $this->assertDatabaseHas('modify_casts', [
            'name' => 'modify_name',
            'cast_id' => $this->cast1->id,
            'furigana' => 'modify_furigana',
            'sex' => 2,
            'office' => 'modify_office',
            'url' => 'modify_url',
            'twitter' => 'modify_twitter',
            'blog' => 'modify_blog',
        ]);
    }

    /**
     * 声優情報修正申請の異常値テスト
     *
     * @test
     * @return void
     */
    public function testNotExistModifyCastRequestPost()
    {
        $response = $this->post(route('modify_cast_request.post', ['cast_id' => 33333333333333333333333]), [
            'name' => 'modify_name',
            'furigana' => 'modify_furigana',
            'sex' => 2,
            'office' => 'modify_office',
            'url' => 'modify_url',
            'twitter' => 'modify_twitter',
            'blog' => 'modify_blog',
        ]);
        $response->assertStatus(404);
    }

    /**
     * ゲスト時のアニメの情報修正申請のリストページリクエスト時のリダイレクトテスト
     *
     * @test
     * @return void
     */
    public function testGuestModifyListView()
    {
        $response = $this->get(route('modify_request_list.show'));
        $response->assertStatus(403);
    }

    /**
     * ユーザーログイン時のアニメの情報修正申請のリストページリクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testUser2LoginModifyListView()
    {
        $this->actingAs($this->user2);
        $response = $this->get(route('modify_request_list.show'));
        $response->assertStatus(403);
    }

    /**
     * ルートユーザーログイン時のアニメの情報修正申請のリストページリクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testRootLoginModifyAnimeListView()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('modify_request_list.show'));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            '1件目',
            'modify_title',
            2040,
            'https://modify_public_url',
            'modify_twitterId',
            'modify_hashTag',
            'modify_company',
            '2件目',
            $this->modifyAnime1->title,
            '1件目',
            $this->anime->title,
            $this->cast1->name,
            $this->cast2->name,
            $this->cast1->name,
            'modify_cast1',
            '2件目',
            $this->anime1->title,
            'modify_cast1',
            'modify_cast2',
            '1件目',
            $this->modifyCast1->name,
            $this->modifyCast1->furigana,
            $this->modifyCast1->office,
            $this->modifyCast1->url,
            $this->modifyCast1->twitter,
            $this->modifyCast1->blog,
            '2件目',
            $this->modifyCast2->name,
            $this->modifyCast2->furigana,
            $this->modifyCast2->office,
            $this->modifyCast2->url,
            $this->modifyCast2->twitter,
            $this->modifyCast2->blog,
        ]);
    }

    /**
     * ゲスト時のアニメの情報更新リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testGuestModifyAnimeRequestApprove()
    {
        $response = $this->post(route('modify_anime_request.approve', ['modify_anime_id' => $this->modifyAnime->id]), [
            'title' => 'modify_title',
            'year' => 2040,
            'coor' => 4,
            'public_url' => 'https://modify_public_url',
            'twitter' => 'modify_twitterId',
            'hash_tag' => 'modify_hashTag',
            'company' => 'modify_company',
        ]);
        $response->assertStatus(403);
    }

    /**
     * ユーザーログイン時のアニメの情報更新リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testUser2LoginModifyAnimeRequestApprove()
    {
        $this->actingAs($this->user2);
        $response = $this->post(route('modify_anime_request.approve', ['modify_anime_id' => $this->modifyAnime->id]), [
            'title' => 'modify_title',
            'year' => 2040,
            'coor' => 4,
            'public_url' => 'https://modify_public_url',
            'twitter' => 'modify_twitterId',
            'hash_tag' => 'modify_hashTag',
            'company' => 'modify_company',
        ]);
        $response->assertStatus(403);
    }

    /**
     * ルートユーザーログイン時のアニメの情報更新リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testRootLoginModifyAnimeRequestApprove()
    {
        $this->actingAs($this->user1);
        $response = $this->post(route('modify_anime_request.approve', ['modify_anime_id' => $this->modifyAnime->id]), [
            'title' => 'modify_title',
            'year' => 2040,
            'coor' => 4,
            'public_url' => 'https://modify_public_url',
            'twitter' => 'modify_twitterId',
            'hash_tag' => 'modify_hashTag',
            'company' => 'modify_company',
        ]);
        $response->assertRedirect(route('modify_request_list.show'));
        $this->assertDatabaseHas('animes', [
            'id' => $this->anime->id,
            'title' => 'modify_title',
            'year' => 2040,
            'coor' => 4,
            'public_url' => 'https://modify_public_url',
            'twitter' => 'modify_twitterId',
            'hash_tag' => 'modify_hashTag',
            'company' => 'modify_company',
        ]);
        $this->assertDatabaseMissing('modify_animes', [
            'id' => $this->modifyAnime->id,
            'anime_id' => $this->anime->id,
            'title' => 'modify_title',
            'year' => 2040,
            'coor' => 4,
            'public_url' => 'https://modify_public_url',
            'twitter' => 'modify_twitterId',
            'hash_tag' => 'modify_hashTag',
            'company' => 'modify_company',
        ]);
    }

    /**
     * ルートユーザーログイン時のアニメの情報更新リクエスト時の異常値テスト
     *
     * @test
     * @return void
     */
    public function testRootLoginNotExistModifyAnimeRequestApprove()
    {
        $this->actingAs($this->user1);
        $response = $this->post(route('modify_anime_request.approve', ['modify_anime_id' => 33333333333333333333]), [
            'title' => 'modify_title',
            'year' => 2040,
            'coor' => 4,
            'public_url' => 'https://modify_public_url',
            'twitter' => 'modify_twitterId',
            'hash_tag' => 'modify_hashTag',
            'company' => 'modify_company',
        ]);
        $response->assertStatus(404);
    }

    /**
     * ゲスト時のアニメの情報修正申請却下リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testGuestModifyAnimeRequestReject()
    {
        $response = $this->get(route('modify_anime_request.reject', ['modify_anime_id' => $this->modifyAnime->id]));
        $response->assertStatus(403);
    }

    /**
     * ユーザーログイン時のアニメの情報修正申請却下リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testUser2LoginModifyAnimeRequestReject()
    {
        $this->actingAs($this->user2);
        $response = $this->get(route('modify_anime_request.reject', ['modify_anime_id' => $this->modifyAnime->id]));
        $response->assertStatus(403);
    }

    /**
     * ルートユーザーログイン時のアニメの情報修正申請却下時のテスト
     *
     * @test
     * @return void
     */
    public function testRootLoginModifyAnimeRequestReject()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('modify_anime_request.reject', ['modify_anime_id' => $this->modifyAnime->id]));
        $response->assertRedirect(route('modify_request_list.show'));
        $this->assertDatabaseMissing('modify_animes', [
            'id' => $this->modifyAnime->id,
            'anime_id' => $this->anime->id,
            'title' => 'modify_title',
            'year' => 2040,
            'coor' => 4,
            'public_url' => 'https://modify_public_url',
            'twitter' => 'modify_twitterId',
            'hash_tag' => 'modify_hashTag',
            'company' => 'modify_company',
        ]);
    }

    /**
     * ルートユーザーログイン時のアニメの情報修正申請却下時の異常値テスト
     *
     * @test
     * @return void
     */
    public function testRootLoginNotExistModifyAnimeRequestReject()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('modify_anime_request.reject', ['modify_anime_id' => 33333333333333333333333]));
        $response->assertStatus(404);
    }

    /**
     * ゲスト時のアニメの出演声優更新リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testGuestModifyOccupationsRequestApprove()
    {
        $response = $this->post(route('modify_occupations_request.approve', ['anime_id' => $this->anime->id]), [
            $this->cast1->name,
            'modify_cast1',
        ]);
        $response->assertStatus(403);
    }

    /**
     * ユーザーログイン時のアニメの出演声優更新リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testUser2LoginModifyOccupationsRequestApprove()
    {
        $this->actingAs($this->user2);
        $response = $this->post(route('modify_occupations_request.approve', ['anime_id' => $this->anime->id]), [
            $this->cast1->name,
            'modify_cast1',
        ]);
        $response->assertStatus(403);
    }

    /**
     * ルートユーザーログイン時のアニメの出演声優更新リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testRootLoginModifyOccupationsRequestApprove()
    {
        $this->actingAs($this->user1);
        $response = $this->post(route('modify_occupations_request.approve', ['anime_id' => $this->anime->id]), [
            $this->cast1->name,
            'modify_cast1',
        ]);
        $response->assertRedirect(route('modify_request_list.show'));
        $this->assertDatabaseHas('occupations', [
            'anime_id' => $this->anime->id,
            'cast_id' => $this->cast1->id,
        ]);
        $modify_cast1 = Cast::where('name', 'modify_cast1')->first();
        $this->assertDatabaseHas('casts', [
            'id' => $modify_cast1->id,
            'name' => 'modify_cast1',
        ]);
        $this->assertDatabaseHas('occupations', [
            'anime_id' => $this->anime->id,
            'cast_id' => $modify_cast1->id,
        ]);
        $this->assertDatabaseMissing('occupations', [
            'anime_id' => $this->anime->id,
            'cast_id' => $this->cast2->id,
        ]);
        $this->assertDatabaseMissing('modify_occupations', [
            'anime_id' => $this->anime->id,
        ]);
    }

    /**
     * ルートユーザーログイン時のアニメの出演声優更新リクエスト時の異常値テスト
     *
     * @test
     * @return void
     */
    public function testRootLoginNotExistModifyOccupationsRequestApprove()
    {
        $this->actingAs($this->user1);
        $response = $this->post(route('modify_occupations_request.approve', ['anime_id' => 3333333333333333333333]), [
            $this->cast1->name,
            'modify_cast1',
        ]);
        $response->assertStatus(404);
    }

    /**
     * ゲスト時のアニメの出演声優却下リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testGuestModifyOccupationsRequestReject()
    {
        $response = $this->get(route('modify_occupations_request.reject', ['anime_id' => $this->anime->id]));
        $response->assertStatus(403);
    }

    /**
     * ユーザーログイン時のアニメの出演声優却下リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testUser2LoginModifyOccupationsRequestReject()
    {
        $this->actingAs($this->user2);
        $response = $this->get(route('modify_occupations_request.reject', ['anime_id' => $this->anime->id]));
        $response->assertStatus(403);
    }

    /**
     * ルートユーザーログイン時のアニメの出演声優却下リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testRootLoginModifyOccupationsRequestReject()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('modify_occupations_request.reject', ['anime_id' => $this->anime->id]));
        $response->assertRedirect(route('modify_request_list.show'));
        $this->assertDatabaseMissing('modify_occupations', [
            'anime_id' => $this->anime->id,
        ]);
    }

    /**
     * ルートユーザーログイン時のアニメの出演声優却下リクエスト時の異常値テスト
     *
     * @test
     * @return void
     */
    public function testRootLoginNotExistModifyOccupationsRequestReject()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('modify_occupations_request.reject', ['anime_id' => 3333333333333333333333333]));
        $response->assertStatus(404);
    }

    /**
     * ゲスト時の声優情報更新リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testGuestModifyCastRequestApprove()
    {
        $response = $this->post(route('modify_cast_request.approve', ['modify_cast_id' => $this->modifyCast1->id]), [
            'name' => 'modify_name',
            'furigana' => 'modify_furigana',
            'sex' => 2,
            'office' => 'modify_office',
            'url' => 'modify_url',
            'twitter' => 'modify_twitter',
            'blog' => 'modify_blog',
        ]);
        $response->assertStatus(403);
    }

    /**
     * ユーザーログイン時の声優情報更新リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testUser2LoginModifyCastRequestApprove()
    {
        $this->actingAs($this->user2);
        $response = $this->post(route('modify_cast_request.approve', ['modify_cast_id' => $this->modifyCast1->id]), [
            'name' => 'modify_name',
            'furigana' => 'modify_furigana',
            'sex' => 2,
            'office' => 'modify_office',
            'url' => 'modify_url',
            'twitter' => 'modify_twitter',
            'blog' => 'modify_blog',
        ]);
        $response->assertStatus(403);
    }

    /**
     * ルートユーザーログイン時の声優情報更新リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testRootLoginModifyCastRequestApprove()
    {
        $this->actingAs($this->user1);
        $response = $this->post(route('modify_cast_request.approve', ['modify_cast_id' => $this->modifyCast1->id]), [
            'name' => 'modify_name',
            'furigana' => 'modify_furigana',
            'sex' => 2,
            'office' => 'modify_office',
            'url' => 'modify_url',
            'twitter' => 'modify_twitter',
            'blog' => 'modify_blog',
        ]);
        $response->assertRedirect(route('modify_request_list.show'));
        $this->assertDatabaseHas('casts', [
            'id' => $this->cast1->id,
            'name' => 'modify_name',
            'furigana' => 'modify_furigana',
            'sex' => 2,
            'office' => 'modify_office',
            'url' => 'modify_url',
            'twitter' => 'modify_twitter',
            'blog' => 'modify_blog',
        ]);
        $this->assertDatabaseMissing('modify_casts', [
            'id' => $this->modifyCast1->id,
            'cast_id' => $this->cast1->id,
        ]);
    }

    /**
     * ルートユーザーログイン時の声優情報更新リクエスト時の異常値テスト
     *
     * @test
     * @return void
     */
    public function testRootLoginNotExistModifyCastRequestApprove()
    {
        $this->actingAs($this->user1);
        $response = $this->post(route('modify_cast_request.approve', ['modify_cast_id' => 3333333333333333333333]), [
            'name' => 'modify_name',
            'furigana' => 'modify_furigana',
            'sex' => 2,
            'office' => 'modify_office',
            'url' => 'modify_url',
            'twitter' => 'modify_twitter',
            'blog' => 'modify_blog',
        ]);
        $response->assertStatus(404);
    }

    /**
     * ゲスト時の声優情報修正申請却下リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testGuestModifyCastRequestReject()
    {
        $response = $this->get(route('modify_cast_request.reject', ['modify_cast_id' => $this->modifyCast1->id]));
        $response->assertStatus(403);
    }

    /**
     * ユーザーログイン時の声優情報修正申請却下リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testUser2LoginModifyCastRequestReject()
    {
        $this->actingAs($this->user2);
        $response = $this->get(route('modify_cast_request.reject', ['modify_cast_id' => $this->modifyCast1->id]));
        $response->assertStatus(403);
    }

    /**
     * ルートユーザーログイン時の声優情報修正申請却下時のテスト
     *
     * @test
     * @return void
     */
    public function testRootLoginModifyCastRequestReject()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('modify_cast_request.reject', ['modify_cast_id' => $this->modifyCast1->id]));
        $response->assertRedirect(route('modify_request_list.show'));
        $this->assertDatabaseMissing('modify_casts', [
            'id' => $this->modifyCast1->id,
            'cast_id' => $this->cast1->id,
        ]);
    }

    /**
     * ルートユーザーログイン時の声優情報修正申請却下時の異常値テスト
     *
     * @test
     * @return void
     */
    public function testRootLoginNotExistModifyCastRequestReject()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('modify_cast_request.reject', ['modify_cast_id' => 333333333333333333333333333]));
        $response->assertStatus(404);
    }

    /**
     * アニメ削除申請ページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testDeleteAnimeRequestView()
    {
        $response = $this->get(route('delete_anime_request.show', ['anime_id' => $this->anime->id]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->anime->title,
            '削除事由',
        ]);
    }

    /**
     * アニメ削除申請ページの表示の異常値テスト
     *
     * @test
     * @return void
     */
    public function testNotExistDeleteAnimeRequestView()
    {
        $response = $this->get(route('delete_anime_request.show', ['anime_id' => 3333333333333333]));
        $response->assertStatus(404);
    }

    /**
     * アニメ削除申請のテスト
     *
     * @test
     * @return void
     */
    public function testDeleteAnimeRequestPost()
    {
        $response = $this->post(route('delete_anime_request.post', ['anime_id' => $this->anime->id,]), [
            'remark' => 'remark_comment',
        ]);
        $this->assertDatabaseHas('delete_animes', [
            'anime_id' => $this->anime->id,
            'remark' => 'remark_comment',
        ]);
    }

    /**
     * アニメ削除申請の異常値テスト
     *
     * @test
     * @return void
     */
    public function testNotExistDeleteAnimeRequestPost()
    {
        $response = $this->post(route('delete_anime_request.post', ['anime_id' => 3333333333333333333333333]), [
            'remark' => 'remark_comment',
        ]);
        $response->assertStatus(404);
    }

    /**
     * ゲスト時のアニメ削除リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testGuestDeleteAnimeRequestApprove()
    {
        $response = $this->post(route('delete_anime_request.approve', ['delete_anime_id' => $this->deleteAnime1->id]));
        $response->assertStatus(403);
    }

    /**
     * ユーザーログイン時のアニメ削除リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testUser2LoginDeleteAnimeRequestApprove()
    {
        $this->actingAs($this->user2);
        $response = $this->post(route('delete_anime_request.approve', ['delete_anime_id' => $this->deleteAnime1->id]));
        $response->assertStatus(403);
    }

    /**
     * ルートユーザーログイン時のアニメ削除リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testRootLoginDeleteAnimeRequestApprove()
    {
        $this->actingAs($this->user1);
        $response = $this->post(route('delete_anime_request.approve', ['delete_anime_id' => $this->deleteAnime1->id]));
        $response->assertRedirect(route('modify_request_list.show'));
        $this->assertDatabaseMissing('animes', [
            'id' => $this->anime1->id,
        ]);
        $this->assertDatabaseMissing('delete_animes', [
            'id' => $this->deleteAnime1->id,
        ]);
    }

    /**
     * ルートユーザーログイン時のアニメ削除リクエスト時の異常値テスト
     *
     * @test
     * @return void
     */
    public function testRootLoginNotExistDeleteAnimeRequestApprove()
    {
        $this->actingAs($this->user1);
        $response = $this->post(route('delete_anime_request.approve', ['delete_anime_id' => 33333333333333333333]));
        $response->assertStatus(404);
    }

    /**
     * ゲスト時のアニメ削除申請却下リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testGuestDeleteAnimeRequestReject()
    {
        $response = $this->get(route('delete_anime_request.reject', ['delete_anime_id' => $this->deleteAnime1->id]));
        $response->assertStatus(403);
    }

    /**
     * ユーザーログイン時のアニメ削除申請却下リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testUser2LoginDeleteAnimeRequestReject()
    {
        $this->actingAs($this->user2);
        $response = $this->get(route('delete_anime_request.reject', ['delete_anime_id' => $this->deleteAnime1->id]));
        $response->assertStatus(403);
    }

    /**
     * ルートユーザーログイン時のアニメ削除申請却下時のテスト
     *
     * @test
     * @return void
     */
    public function testRootLoginDeleteAnimeRequestReject()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('delete_anime_request.reject', ['delete_anime_id' => $this->deleteAnime1->id]));
        $response->assertRedirect(route('modify_request_list.show'));
        $this->assertDatabaseMissing('delete_animes', [
            'id' => $this->deleteAnime1->id,
            'anime_id' => $this->anime1->id,
        ]);
    }

    /**
     * ルートユーザーログイン時のアニメ削除申請却下時の異常値テスト
     *
     * @test
     * @return void
     */
    public function testRootLoginNotExistDeleteAnimeRequestReject()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('delete_anime_request.reject', ['delete_anime_id' => 33333333333333333333333]));
        $response->assertStatus(404);
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
        $response = $this->get((route('anime.delete', ['anime_id' => $this->anime->id])));
        $response->assertRedirect(route('index.show'));
        $this->assertDatabaseMissing('animes', [
            'id' => $this->anime->id,
        ]);
    }

    /**
     * ルートログイン時のアニメ削除の異常値テスト
     *
     * @test
     * @return void
     */
    public function testRootLoginNotExistAnimeDelete()
    {
        $this->actingAs($this->user1);
        $response = $this->get((route('anime.delete', ['anime_id' => 3333333333333333333333])));
        $response->assertStatus(404);
    }

    /**
     * ゲスト時のアニメ削除のテスト
     *
     * @test
     * @return void
     */
    public function testGuestAnimeDelete()
    {
        $response = $this->get((route('anime.delete', ['anime_id' => $this->anime->id])));
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
        $response = $this->get((route('anime.delete', ['anime_id' => $this->anime->id])));
        $response->assertStatus(403);
    }

    /**
     * アニメ追加申請ページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testAddAnimeRequestView()
    {
        $response = $this->get(route('add_anime_request.show'));
        $response->assertStatus(200);
        $response->assertSee('アニメの追加申請');
    }

    /**
     * アニメ追加申請のテスト
     *
     * @test
     * @return void
     */
    public function testAddAnimeRequestPost()
    {
        $response = $this->post(route('add_anime_request.post'), [
            'title' => 'add_title_post',
            'year' => 2040,
            'coor' => 4,
            'public_url' => 'https://add_public_url',
            'twitter' => 'add_twitterId',
            'hash_tag' => 'add_hashTag',
            'company' => 'add_company',
        ]);
        $this->assertDatabaseHas('add_animes', [
            'title' => 'add_title_post',
            'year' => 2040,
            'coor' => 4,
            'public_url' => 'https://add_public_url',
            'twitter' => 'add_twitterId',
            'hash_tag' => 'add_hashTag',
            'company' => 'add_company',
        ]);
    }

    /**
     * ゲスト時のアニメ追加リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testGuestAddAnimeRequestApprove()
    {
        $response = $this->post(route('add_anime_request.approve', ['add_anime_id' => $this->addAnime->id]), [
            'title' => 'add_title',
            'year' => 2040,
            'coor' => 4,
            'public_url' => 'https://add_public_url',
            'twitter' => 'add_twitterId',
            'hash_tag' => 'add_hashTag',
            'company' => 'add_company',
        ]);
        $response->assertStatus(403);
    }

    /**
     * ユーザーログイン時のアニメ追加リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testUser2LoginAddAnimeRequestApprove()
    {
        $this->actingAs($this->user2);
        $response = $this->post(route('add_anime_request.approve', ['add_anime_id' => $this->addAnime->id]), [
            'title' => 'add_title',
            'year' => 2040,
            'coor' => 4,
            'public_url' => 'https://add_public_url',
            'twitter' => 'add_twitterId',
            'hash_tag' => 'add_hashTag',
            'company' => 'add_company',
        ]);
        $response->assertStatus(403);
    }

    /**
     * ルートユーザーログイン時のアニメ追加リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testRootLoginAddAnimeRequestApprove()
    {
        $this->actingAs($this->user1);
        $response = $this->post(route('add_anime_request.approve', ['add_anime_id' => $this->addAnime->id]), [
            'title' => 'add_title',
            'year' => 2040,
            'coor' => 4,
            'public_url' => 'https://add_public_url',
            'twitter' => 'add_twitterId',
            'hash_tag' => 'add_hashTag',
            'company' => 'add_company',
        ]);
        $response->assertRedirect(route('modify_request_list.show'));
        $this->assertDatabaseHas('animes', [
            'title' => 'add_title',
            'year' => 2040,
            'coor' => 4,
            'public_url' => 'https://add_public_url',
            'twitter' => 'add_twitterId',
            'hash_tag' => 'add_hashTag',
            'company' => 'add_company',
        ]);
        $this->assertDatabaseMissing('add_animes', [
            'id' => $this->addAnime->id,
        ]);
    }

    /**
     * ルートユーザーログイン時のアニメ追加リクエスト時の異常値テスト
     *
     * @test
     * @return void
     */
    public function testRootLoginNotExistAddAnimeRequestApprove()
    {
        $this->actingAs($this->user1);
        $response = $this->post(route('add_anime_request.approve', ['add_anime_id' => 33333333333333333333]), [
            'title' => 'add_title',
            'year' => 2040,
            'coor' => 4,
            'public_url' => 'https://add_public_url',
            'twitter' => 'add_twitterId',
            'hash_tag' => 'add_hashTag',
            'company' => 'add_company',
        ]);
        $response->assertStatus(404);
    }

    /**
     * ゲスト時のアニメ追加申請却下リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testGuestAddAnimeRequestReject()
    {
        $response = $this->get(route('add_anime_request.reject', ['add_anime_id' => $this->addAnime->id]));
        $response->assertStatus(403);
    }

    /**
     * ユーザーログイン時のアニメ追加申請却下リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testUser2LoginAddAnimeRequestReject()
    {
        $this->actingAs($this->user2);
        $response = $this->get(route('add_anime_request.reject', ['add_anime_id' => $this->addAnime->id]));
        $response->assertStatus(403);
    }

    /**
     * ルートユーザーログイン時のアニメ追加申請却下時のテスト
     *
     * @test
     * @return void
     */
    public function testRootLoginAddAnimeRequestReject()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('add_anime_request.reject', ['add_anime_id' => $this->addAnime->id]));
        $response->assertRedirect(route('modify_request_list.show'));
        $this->assertDatabaseMissing('add_animes', [
            'id' => $this->addAnime->id,
        ]);
    }

    /**
     * ルートユーザーログイン時のアニメ追加申請却下時の異常値テスト
     *
     * @test
     * @return void
     */
    public function testRootLoginNotExistAddAnimeRequestReject()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('add_anime_request.reject', ['add_anime_id' => 33333333333333333333333]));
        $response->assertStatus(404);
    }
}
