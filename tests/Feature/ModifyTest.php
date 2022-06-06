<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\ModifyAnime;
use App\Models\ModifyCast;
use App\Models\DeleteAnime;
use App\Models\AddAnime;
use App\Models\AddCast;
use App\Models\DeleteCast;
use App\Models\DeleteCompany;
use App\Models\Anime;
use App\Models\Cast;
use App\Models\Company;
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
    private AddAnime $addAnimeDeleted;
    private AddCast $addCast;
    private AddCast $addCastDeleted;
    private DeleteCast $deleteCast;
    private DeleteCompany $deleteCompany;
    private Cast $cast1;
    private Cast $cast2;
    private Company $company1;
    private Company $company2;
    private Company $company3;
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
        $this->anime->actCasts()->attach($this->cast1->id);
        $this->anime->actCasts()->attach($this->cast2->id);

        $this->deleteAnime1 = DeleteAnime::create(['anime_id' => $this->anime1->id, 'remark' => 'remark1']);

        $this->addAnime = AddAnime::create([
            'title' => 'add_title',
            'title_short' => 'add_title_short',
            'furigana' => 'add_furigana',
            'year' => 2040,
            'coor' => 4,
            'number_of_episode' => 13,
            'public_url' => 'https://add_public_url',
            'twitter' => 'add_twitterId',
            'hash_tag' => 'add_hashTag',
            'company1' => 'add_company1',
            'company2' => 'add_company2',
            'company3' => 'add_company3',
            'city_name' => 'add_city_name',
            'media_category' => 1,
            'summary' => 'add_summary',
            'd_anime_store_id' => 'add_d_anime_store_id',
            'amazon_prime_video_id' => 'add_amazon_prime_video_id',
            'unext_id' => 'add_unext_id',
            'fod_id' => 'add_fod_id',
            'abema_id' => 'add_abema_id',
            'disney_plus_id' => 'add_disney_plus_id',
            'remark' => 'add_remark',
        ]);

        $this->addAnimeDeleted = AddAnime::create([
            'title' => 'add_anime_deleted',
            'year' => 2040,
            'delete_flag' => true,
            'anime_id' => 3
        ]);

        $this->addCast = AddCast::create([
            'name' => 'add_name',
            'furigana' => 'add_furigana',
            'sex' => 2,
            'birth' => '2000年4月13日',
            'birthplace' => '千葉県',
            'blood_type' => 'AB',
            'office' => 'add_office',
            'url' => 'add_url',
            'twitter' => 'add_twitter',
            'blog' => 'add_blog',
            'blog_url' => 'add_blog_url',
            'remark' => 'add_remark',
        ]);

        $this->addCastDeleted = AddCast::create([
            'name' => 'add_cast_deleted',
            'delete_flag' => true,
            'cast_id' => 1
        ]);

        $this->deleteCast = DeleteCast::create(['cast_id' => $this->cast1->id, 'remark' => 'remark2']);

        $this->user1 = User::factory()->create(['name' => 'root']);
        $this->user2 = User::factory()->create();

        $this->company1 = Company::factory()->create();
        $this->company2 = Company::factory()->create();
        $this->company3 = Company::factory()->create();
        $this->anime->companies()->attach($this->company1->id);
        $this->anime->companies()->attach($this->company2->id);
        $this->anime->companies()->attach($this->company3->id);

        $this->deleteCompany = DeleteCompany::create(['company_id' => $this->company1->id, 'remark' => 'remark2']);
    }

    /**
     * アニメの基本情報変更申請ページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testModifyAnimeRequestView()
    {
        $response = $this->get(route('modify_anime_request.show', [
            'anime_id' => $this->anime->id,
        ]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->anime->title,
            $this->anime->title,
            $this->anime->furigana,
            $this->anime->furigana,
            $this->anime->title_short,
            $this->anime->title_short,
            $this->anime->year,
            $this->anime->year,
            $this->anime->coor_label,
            $this->anime->number_of_episode,
            $this->anime->number_of_episode,
            $this->anime->public_url,
            $this->anime->public_url,
            $this->anime->twitter,
            $this->anime->twitter,
            $this->anime->hash_tag,
            $this->anime->hash_tag,
            $this->anime->city_name,
            $this->anime->city_name,
            $this->anime->companies[0]->name,
            $this->anime->companies[0]->name,
            $this->anime->companies[1]->name,
            $this->anime->companies[1]->name,
            $this->anime->companies[2]->name,
            $this->anime->companies[2]->name,
            $this->anime->media_category_label,
            $this->anime->d_anime_store_id,
            $this->anime->d_anime_store_id,
            $this->anime->amazon_prime_video_id,
            $this->anime->amazon_prime_video_id,
            $this->anime->fod_id,
            $this->anime->fod_id,
            $this->anime->unext_id,
            $this->anime->unext_id,
            $this->anime->abema_id,
            $this->anime->abema_id,
            $this->anime->disney_plus_id,
            $this->anime->disney_plus_id,
            $this->anime->summary,
            $this->anime->summary,
        ]);
    }

    /**
     * アニメの基本情報変更申請ページの表示の異常値テスト
     *
     * @test
     * @return void
     */
    public function testNotExistModifyAnimeRequestView()
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
    public function testModifyAnimeRequestPost()
    {
        $response = $this->post(route('modify_anime_request.post', ['anime_id' => $this->anime->id]), [
            'title' => 'modify_title',
            'title_short' => 'modify_title_short',
            'furigana' => 'modify_furigana',
            'year' => 2040,
            'coor' => 4,
            'number_of_episode' => 13,
            'public_url' => 'https://modify_public_url',
            'twitter' => 'modify_twitterId',
            'hash_tag' => 'modify_hashTag',
            'company1' => 'modify_company1',
            'company2' => 'modify_company2',
            'company3' => 'modify_company3',
            'media_category' => 1,
            'city_name' => 'modify_city_name',
            'summary' => 'modify_summary',
            'd_anime_store_id' => 'modify_d_anime_store_id',
            'amazon_prime_video_id' => 'modify_amazon_prime_video_id',
            'unext_id' => 'modify_unext_id',
            'fod_id' => 'modify_fod_id',
            'abema_id' => 'modify_abema_id',
            'disney_plus_id' => 'modify_disney_plus_id',
            'remark' => 'modify_remark',
        ]);
        $response->assertRedirect(route('modify_anime_request.show', [
            'anime_id' => $this->anime->id,
        ]));
        $this->assertDatabaseHas('modify_animes', [
            'anime_id' => 1,
            'title' => 'modify_title',
            'title_short' => 'modify_title_short',
            'furigana' => 'modify_furigana',
            'year' => 2040,
            'coor' => 4,
            'number_of_episode' => 13,
            'public_url' => 'https://modify_public_url',
            'twitter' => 'modify_twitterId',
            'hash_tag' => 'modify_hashTag',
            'company1' => 'modify_company1',
            'company2' => 'modify_company2',
            'company3' => 'modify_company3',
            'media_category' => 1,
            'city_name' => 'modify_city_name',
            'summary' => 'modify_summary',
            'd_anime_store_id' => 'modify_d_anime_store_id',
            'amazon_prime_video_id' => 'modify_amazon_prime_video_id',
            'unext_id' => 'modify_unext_id',
            'fod_id' => 'modify_fod_id',
            'abema_id' => 'modify_abema_id',
            'disney_plus_id' => 'modify_disney_plus_id',
            'remark' => 'modify_remark',
        ]);
    }

    /**
     * アニメの基本情報変更申請の異常値テスト
     *
     * @test
     * @return void
     */
    public function testNotExistModifyAnimeRequestPost()
    {
        $response = $this->post(route('modify_anime_request.post', ['anime_id' => 333333333333333333333333333]), [
            'title' => 'modify_title',
            'title_short' => 'modify_title_short',
            'furigana' => 'modify_furigana',
            'year' => 2040,
            'coor' => 4,
            'number_of_episode' => 13,
            'public_url' => 'https://modify_public_url',
            'twitter' => 'modify_twitterId',
            'hash_tag' => 'modify_hashTag',
            'company1' => 'modify_company1',
            'company2' => 'modify_company2',
            'company3' => 'modify_company3',
            'media_category' => 1,
            'city_name' => 'modify_city_name',
            'summary' => 'modify_summary',
            'd_anime_store_id' => 'modify_d_anime_store_id',
            'amazon_prime_video_id' => 'modify_amazon_prime_video_id',
            'unext_id' => 'modify_unext_id',
            'fod_id' => 'modify_fod_id',
            'abema_id' => 'modify_abema_id',
            'disney_plus_id' => 'modify_disney_plus_id',
            'remark' => 'modify_remark',
        ]);
        $response->assertStatus(404);
    }

    /**
     * 声優情報変更申請ページの表示のテスト
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
            $this->cast1->birth,
            $this->cast1->birthplace,
            $this->cast1->blood_type,
            $this->cast1->office,
            $this->cast1->url,
            $this->cast1->twitter,
            $this->cast1->blog,
            $this->cast1->blog_url,
        ]);
    }

    /**
     * 声優情報変更申請ページの表示の異常値テスト
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
     * 声優情報変更申請のテスト
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
            'birth' => '2000年4月13日',
            'birthplace' => '千葉県',
            'blood_type' => 'AB',
            'office' => 'modify_office',
            'url' => 'modify_url',
            'twitter' => 'modify_twitter',
            'blog' => 'modify_blog',
            'blog_url' => 'modify_blog_url',
        ]);
        $this->assertDatabaseHas('modify_casts', [
            'name' => 'modify_name',
            'cast_id' => $this->cast1->id,
            'furigana' => 'modify_furigana',
            'sex' => 2,
            'birth' => '2000年4月13日',
            'birthplace' => '千葉県',
            'blood_type' => 'AB',
            'office' => 'modify_office',
            'url' => 'modify_url',
            'twitter' => 'modify_twitter',
            'blog' => 'modify_blog',
            'blog_url' => 'modify_blog_url',
        ]);
    }

    /**
     * 声優情報変更申請の異常値テスト
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
            'birth' => '2000年4月13日',
            'birthplace' => '千葉県',
            'blood_type' => 'AB',
            'office' => 'modify_office',
            'url' => 'modify_url',
            'twitter' => 'modify_twitter',
            'blog' => 'modify_blog',
            'blog_url' => 'modify_blog_url',
        ]);
        $response->assertStatus(404);
    }

    /**
     * ルートログイン時の変更申請のリストページリクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testRootLoginModifyRequestListView()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('modify_request_list.show'));
        $response->assertStatus(200);
        $response->assertSeeInOrder(['許可', '却下']);
    }

    /**
     * 変更申請のリストページリクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testModifyRequestListView()
    {
        $response = $this->get(route('modify_request_list.show'));
        $response->assertStatus(200);
        $response->assertDontSee('許可');
        $response->assertDontSee('却下');
    }

    /**
     * 変更申請のリストページのアニメの基本情報変更申請の表示のテスト
     *
     * @test
     * @return void
     */
    public function testModifyAnimeOfModifyRequestListView()
    {
        $response = $this->get(route('modify_request_list.show'));
        $response->assertSeeInOrder([
            '1件目',
            $this->anime->title,
            $this->anime->title,
            $this->modifyAnime->title,
            $this->anime->furigana,
            $this->modifyAnime->furigana,
            $this->anime->title_short,
            $this->modifyAnime->title_short,
            $this->anime->year,
            $this->modifyAnime->year,
            $this->anime->number_of_episode,
            $this->modifyAnime->number_of_episode,
            $this->anime->public_url,
            $this->modifyAnime->public_url,
            $this->anime->twitter,
            $this->modifyAnime->twitter,
            $this->anime->hash_tag,
            $this->modifyAnime->hash_tag,
            $this->anime->companies[0]->name,
            $this->modifyAnime->company1,
            $this->anime->companies[1]->name,
            $this->modifyAnime->company2,
            $this->anime->companies[2]->name,
            $this->modifyAnime->company3,
            $this->modifyAnime->media_category_label,
            $this->anime->d_anime_store_id,
            $this->modifyAnime->d_anime_store_id,
            $this->anime->amazon_prime_video_id,
            $this->modifyAnime->amazon_prime_video_id,
            $this->anime->fod_id,
            $this->modifyAnime->fod_id,
            $this->anime->unext_id,
            $this->modifyAnime->unext_id,
            $this->anime->abema_id,
            $this->modifyAnime->abema_id,
            $this->anime->disney_plus_id,
            $this->modifyAnime->disney_plus_id,
            $this->anime->summary,
            $this->modifyAnime->summary,
            $this->modifyAnime->remark,
            '2件目',
            $this->modifyAnime1->title,
        ]);
    }

    /**
     * 変更申請のリストページの声優情報変更申請の表示のテスト
     *
     * @test
     * @return void
     */
    public function testModifyCastOfModifyRequestListView()
    {
        $response = $this->get(route('modify_request_list.show'));
        $response->assertSeeInOrder([
            '1件目',
            $this->modifyCast1->name,
            $this->modifyCast1->furigana,
            $this->modifyCast1->birth,
            $this->modifyCast1->birthplace,
            $this->modifyCast1->blood_type,
            $this->modifyCast1->office,
            $this->modifyCast1->url,
            $this->modifyCast1->twitter,
            $this->modifyCast1->blog,
            $this->modifyCast1->blog_url,
            '2件目',
            $this->modifyCast2->name,
        ]);
    }

    /**
     * 変更申請のリストページのアニメの削除申請の表示のテスト
     *
     * @test
     * @return void
     */
    public function testDeleteAnimeOfModifyRequestListView()
    {
        $response = $this->get(route('modify_request_list.show'));
        $response->assertSeeInOrder([
            '1件目',
            $this->deleteAnime1->anime->title,
            $this->deleteAnime1->remark,
        ]);
    }

    /**
     * 変更申請のリストページのアニメの追加申請の表示のテスト
     *
     * @test
     * @return void
     */
    public function testAddAnimeOfModifyRequestListView()
    {
        $response = $this->get(route('modify_request_list.show'));
        $response->assertSeeInOrder([
            '1件目',
            $this->addAnime->title,
            $this->addAnime->furigana,
            $this->addAnime->title_short,
            $this->addAnime->year,
            $this->addAnime->number_of_episode,
            $this->addAnime->public_url,
            $this->addAnime->twitter,
            $this->addAnime->hash_tag,
            $this->addAnime->company1,
            $this->addAnime->company2,
            $this->addAnime->company3,
            $this->addAnime->media_category_label,
            $this->addAnime->d_anime_store_id,
            $this->addAnime->amazon_prime_video_id,
            $this->addAnime->fod_id,
            $this->addAnime->unext_id,
            $this->addAnime->abema_id,
            $this->addAnime->disney_plus_id,
            $this->addAnime->summary,
            $this->addAnime->remark,
        ]);
        $response->assertDontSee($this->addAnimeDeleted->title);
    }

    /**
     * 変更申請のリストページの声優の追加申請の表示のテスト
     *
     * @test
     * @return void
     */
    public function testAddCastOfModifyRequestListView()
    {
        $response = $this->get(route('modify_request_list.show'));
        $response->assertSeeInOrder([
            '1件目',
            $this->addCast->name,
            $this->addCast->furigana,
            $this->addCast->birth,
            $this->addCast->birthplace,
            $this->addCast->office,
            $this->addCast->url,
            $this->addCast->twitter,
            $this->addCast->blog,
            $this->addCast->blog_url,
            $this->addCast->remark,
        ]);
        $response->assertDontSee($this->addCastDeleted->name);
    }

    /**
     * 変更申請のリストページの声優の削除申請の表示のテスト
     *
     * @test
     * @return void
     */
    public function testDeleteCastOfModifyRequestListView()
    {
        $response = $this->get(route('modify_request_list.show'));
        $response->assertSeeInOrder([
            '1件目',
            $this->deleteCast->cast->name,
            $this->deleteCast->remark,
        ]);
    }

    /**
     * 変更申請のリストページの会社の削除申請の表示のテスト
     *
     * @test
     * @return void
     */
    public function testDeleteCompanyOfModifyRequestListView()
    {
        $response = $this->get(route('modify_request_list.show'));
        $response->assertSeeInOrder([
            '1件目',
            $this->deleteCompany->company->name,
            $this->deleteCompany->remark,
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
            'title_short' => 'modify_title_short',
            'furigana' => 'modify_furigana',
            'year' => 2040,
            'coor' => 4,
            'number_of_episode' => 13,
            'public_url' => 'https://modify_public_url',
            'twitter' => 'modify_twitterId',
            'hash_tag' => 'modify_hashTag',
            'company1' => 'modify_company1',
            'company2' => 'modify_company2',
            'company3' => 'modify_company3',
            'media_category' => 2,
            'city_name' => 'modify_city_name',
            'summary' => 'modify_summary',
            'd_anime_store_id' => 'modify_d_anime_store_id',
            'amazon_prime_video_id' => 'modify_amazon_prime_video_id',
            'unext_id' => 'modify_unext_id',
            'fod_id' => 'modify_fod_id',
            'abema_id' => 'modify_abema_id',
            'disney_plus_id' => 'modify_disney_plus_id',
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
            'title_short' => 'modify_title_short',
            'furigana' => 'modify_furigana',
            'year' => 2040,
            'coor' => 4,
            'number_of_episode' => 13,
            'public_url' => 'https://modify_public_url',
            'twitter' => 'modify_twitterId',
            'hash_tag' => 'modify_hashTag',
            'company1' => 'modify_company1',
            'company2' => 'modify_company2',
            'company3' => 'modify_company3',
            'media_category' => 2,
            'city_name' => 'modify_city_name',
            'summary' => 'modify_summary',
            'd_anime_store_id' => 'modify_d_anime_store_id',
            'amazon_prime_video_id' => 'modify_amazon_prime_video_id',
            'unext_id' => 'modify_unext_id',
            'fod_id' => 'modify_fod_id',
            'abema_id' => 'modify_abema_id',
            'disney_plus_id' => 'modify_disney_plus_id',
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
            'title_short' => 'modify_title_short',
            'furigana' => 'modify_furigana',
            'year' => 2040,
            'coor' => 4,
            'number_of_episode' => 13,
            'public_url' => 'https://modify_public_url',
            'twitter' => 'modify_twitterId',
            'hash_tag' => 'modify_hashTag',
            'company1' => 'modify_company1',
            'company2' => 'modify_company2',
            'company3' => 'modify_company3',
            'media_category' => 2,
            'city_name' => 'modify_city_name',
            'summary' => 'modify_summary',
            'd_anime_store_id' => 'modify_d_anime_store_id',
            'amazon_prime_video_id' => 'modify_amazon_prime_video_id',
            'unext_id' => 'modify_unext_id',
            'fod_id' => 'modify_fod_id',
            'abema_id' => 'modify_abema_id',
            'disney_plus_id' => 'modify_disney_plus_id',
        ]);
        $response->assertRedirect(route('modify_request_list.show'));
        $this->assertDatabaseHas('animes', [
            'id' => $this->anime->id,
            'title' => 'modify_title',
            'title_short' => 'modify_title_short',
            'furigana' => 'modify_furigana',
            'year' => 2040,
            'coor' => 4,
            'number_of_episode' => 13,
            'public_url' => 'https://modify_public_url',
            'twitter' => 'modify_twitterId',
            'hash_tag' => 'modify_hashTag',
            'media_category' => 2,
            'city_name' => 'modify_city_name',
            'summary' => 'modify_summary',
            'd_anime_store_id' => 'modify_d_anime_store_id',
            'amazon_prime_video_id' => 'modify_amazon_prime_video_id',
            'unext_id' => 'modify_unext_id',
            'fod_id' => 'modify_fod_id',
            'abema_id' => 'modify_abema_id',
            'disney_plus_id' => 'modify_disney_plus_id',
        ]);
        $this->assertDatabaseMissing('modify_animes', [
            'id' => $this->modifyAnime->id,
        ]);
        $this->assertDatabaseHas('companies', [
            'name' => 'modify_company1'
        ]);
        $this->assertDatabaseHas('companies', [
            'name' => 'modify_company2'
        ]);
        $this->assertDatabaseHas('companies', [
            'name' => 'modify_company3'
        ]);
        $company1 = Company::where('name', 'modify_company1')->first();
        $company2 = Company::where('name', 'modify_company2')->first();
        $company3 = Company::where('name', 'modify_company3')->first();
        $this->assertDatabaseHas('anime_company', [
            'anime_id' => $this->anime->id,
            'company_id' => $company1->id,
        ]);
        $this->assertDatabaseHas('anime_company', [
            'anime_id' => $this->anime->id,
            'company_id' => $company2->id,
        ]);
        $this->assertDatabaseHas('anime_company', [
            'anime_id' => $this->anime->id,
            'company_id' => $company3->id,
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
     * ゲスト時のアニメの情報変更申請却下リクエスト時のテスト
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
     * ユーザーログイン時のアニメの情報変更申請却下リクエスト時のテスト
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
     * ルートユーザーログイン時のアニメの情報変更申請却下時のテスト
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
        ]);
    }

    /**
     * ルートユーザーログイン時のアニメの情報変更申請却下時の異常値テスト
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
            'birth' => '2000年4月13日',
            'birthplace' => '千葉県',
            'blood_type' => 'AB',
            'office' => 'modify_office',
            'url' => 'modify_url',
            'twitter' => 'modify_twitter',
            'blog' => 'modify_blog',
            'blog_url' => 'modify_blog_url',
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
            'birth' => '2000年4月13日',
            'birthplace' => '千葉県',
            'blood_type' => 'AB',
            'office' => 'modify_office',
            'url' => 'modify_url',
            'twitter' => 'modify_twitter',
            'blog' => 'modify_blog',
            'blog_url' => 'modify_blog_url',
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
            'birth' => '2000年4月13日',
            'birthplace' => '千葉県',
            'blood_type' => 'AB',
            'office' => 'modify_office',
            'url' => 'modify_url',
            'twitter' => 'modify_twitter',
            'blog' => 'modify_blog',
            'blog_url' => 'modify_blog_url',
        ]);
        $response->assertRedirect(route('modify_request_list.show'));
        $this->assertDatabaseHas('casts', [
            'id' => $this->cast1->id,
            'name' => 'modify_name',
            'furigana' => 'modify_furigana',
            'sex' => 2,
            'birth' => '2000年4月13日',
            'birthplace' => '千葉県',
            'blood_type' => 'AB',
            'office' => 'modify_office',
            'url' => 'modify_url',
            'twitter' => 'modify_twitter',
            'blog' => 'modify_blog',
            'blog_url' => 'modify_blog_url',
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
            'birth' => '2000年4月13日',
            'birthplace' => '千葉県',
            'blood_type' => 'AB',
            'office' => 'modify_office',
            'url' => 'modify_url',
            'twitter' => 'modify_twitter',
            'blog' => 'modify_blog',
            'blog_url' => 'modify_blog_url',
        ]);
        $response->assertStatus(404);
    }

    /**
     * ゲスト時の声優情報変更申請却下リクエスト時のテスト
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
     * ユーザーログイン時の声優情報変更申請却下リクエスト時のテスト
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
     * ルートユーザーログイン時の声優情報変更申請却下時のテスト
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
     * ルートユーザーログイン時の声優情報変更申請却下時の異常値テスト
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
            'title' => 'add_post_title',
            'title_short' => 'add_post_title_short',
            'furigana' => 'add_post_furigana',
            'year' => 2040,
            'coor' => 4,
            'number_of_episode' => 13,
            'public_url' => 'https://add_post_public_url',
            'twitter' => 'add_post_twitterId',
            'hash_tag' => 'add_post_hashTag',
            'company1' => 'add_post_company1',
            'company2' => 'add_post_company2',
            'company3' => 'add_post_company3',
            'media_category' => 2,
            'city_name' => 'add_post_city_name',
            'summary' => 'add_post_summary',
            'd_anime_store_id' => 'add_post_d_anime_store_id',
            'amazon_prime_video_id' => 'add_post_amazon_prime_video_id',
            'unext_id' => 'add_post_unext_id',
            'fod_id' => 'add_post_fod_id',
            'abema_id' => 'add_post_abema_id',
            'disney_plus_id' => 'add_post_disney_plus_id',
            'remark' => 'add_post_remark',
        ]);
        $this->assertDatabaseHas('add_animes', [
            'title' => 'add_post_title',
            'title_short' => 'add_post_title_short',
            'furigana' => 'add_post_furigana',
            'year' => 2040,
            'coor' => 4,
            'number_of_episode' => 13,
            'public_url' => 'https://add_post_public_url',
            'twitter' => 'add_post_twitterId',
            'hash_tag' => 'add_post_hashTag',
            'company1' => 'add_post_company1',
            'company2' => 'add_post_company2',
            'company3' => 'add_post_company3',
            'media_category' => 2,
            'city_name' => 'add_post_city_name',
            'summary' => 'add_post_summary',
            'd_anime_store_id' => 'add_post_d_anime_store_id',
            'amazon_prime_video_id' => 'add_post_amazon_prime_video_id',
            'unext_id' => 'add_post_unext_id',
            'fod_id' => 'add_post_fod_id',
            'abema_id' => 'add_post_abema_id',
            'disney_plus_id' => 'add_post_disney_plus_id',
            'remark' => 'add_post_remark',
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
            'title' => 'add_post_title',
            'title_short' => 'add_post_title_short',
            'furigana' => 'add_post_furigana',
            'year' => 2040,
            'coor' => 4,
            'number_of_episode' => 13,
            'public_url' => 'https://add_post_public_url',
            'twitter' => 'add_post_twitterId',
            'hash_tag' => 'add_post_hashTag',
            'company1' => 'add_post_company1',
            'company2' => 'add_post_company2',
            'company3' => 'add_post_company3',
            'city_name' => 'add_post_city_name',
            'summary' => 'add_post_summary',
            'd_anime_store_id' => 'add_post_d_anime_store_id',
            'amazon_prime_video_id' => 'add_post_amazon_prime_video_id',
            'unext_id' => 'add_post_unext_id',
            'fod_id' => 'add_post_fod_id',
            'abema_id' => 'add_post_abema_id',
            'disney_plus_id' => 'add_post_disney_plus_id',
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
            'title' => 'add_post_title',
            'title_short' => 'add_post_title_short',
            'furigana' => 'add_post_furigana',
            'year' => 2040,
            'coor' => 4,
            'number_of_episode' => 13,
            'public_url' => 'https://add_post_public_url',
            'twitter' => 'add_post_twitterId',
            'hash_tag' => 'add_post_hashTag',
            'company1' => 'add_post_company1',
            'company2' => 'add_post_company2',
            'company3' => 'add_post_company3',
            'city_name' => 'add_post_city_name',
            'summary' => 'add_post_summary',
            'd_anime_store_id' => 'add_post_d_anime_store_id',
            'amazon_prime_video_id' => 'add_post_amazon_prime_video_id',
            'unext_id' => 'add_post_unext_id',
            'fod_id' => 'add_post_fod_id',
            'abema_id' => 'add_post_abema_id',
            'disney_plus_id' => 'add_post_disney_plus_id',
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
            'title' => 'add_post_title',
            'title_short' => 'add_post_title_short',
            'furigana' => 'add_post_furigana',
            'year' => 2040,
            'coor' => 4,
            'number_of_episode' => 13,
            'public_url' => 'https://add_post_public_url',
            'twitter' => 'add_post_twitterId',
            'hash_tag' => 'add_post_hashTag',
            'company1' => 'add_post_company1',
            'company2' => 'add_post_company2',
            'company3' => 'add_post_company3',
            'media_category' => 2,
            'city_name' => 'add_post_city_name',
            'summary' => 'add_post_summary',
            'd_anime_store_id' => 'add_post_d_anime_store_id',
            'amazon_prime_video_id' => 'add_post_amazon_prime_video_id',
            'unext_id' => 'add_post_unext_id',
            'fod_id' => 'add_post_fod_id',
            'abema_id' => 'add_post_abema_id',
            'disney_plus_id' => 'add_post_disney_plus_id',
        ]);
        $response->assertRedirect(route('modify_request_list.show'));
        $this->assertDatabaseHas('animes', [
            'title' => 'add_post_title',
            'title_short' => 'add_post_title_short',
            'furigana' => 'add_post_furigana',
            'year' => 2040,
            'coor' => 4,
            'number_of_episode' => 13,
            'public_url' => 'https://add_post_public_url',
            'twitter' => 'add_post_twitterId',
            'hash_tag' => 'add_post_hashTag',
            'media_category' => 2,
            'city_name' => 'add_post_city_name',
            'summary' => 'add_post_summary',
            'd_anime_store_id' => 'add_post_d_anime_store_id',
            'amazon_prime_video_id' => 'add_post_amazon_prime_video_id',
            'unext_id' => 'add_post_unext_id',
            'fod_id' => 'add_post_fod_id',
            'abema_id' => 'add_post_abema_id',
            'disney_plus_id' => 'add_post_disney_plus_id',
        ]);
        $this->assertDatabaseHas('add_animes', [
            'id' => $this->addAnime->id,
            'title' => 'add_post_title',
            'title_short' => 'add_post_title_short',
            'furigana' => 'add_post_furigana',
            'year' => 2040,
            'coor' => 4,
            'number_of_episode' => 13,
            'public_url' => 'https://add_post_public_url',
            'twitter' => 'add_post_twitterId',
            'hash_tag' => 'add_post_hashTag',
            'company1' => 'add_post_company1',
            'company2' => 'add_post_company2',
            'company3' => 'add_post_company3',
            'media_category' => 2,
            'city_name' => 'add_post_city_name',
            'summary' => 'add_post_summary',
            'd_anime_store_id' => 'add_post_d_anime_store_id',
            'amazon_prime_video_id' => 'add_post_amazon_prime_video_id',
            'unext_id' => 'add_post_unext_id',
            'fod_id' => 'add_post_fod_id',
            'abema_id' => 'add_post_abema_id',
            'disney_plus_id' => 'add_post_disney_plus_id',
            'delete_flag' => 1,
        ]);
        $this->assertDatabaseHas('companies', [
            'name' => 'add_post_company1'
        ]);
        $this->assertDatabaseHas('companies', [
            'name' => 'add_post_company2'
        ]);
        $this->assertDatabaseHas('companies', [
            'name' => 'add_post_company3'
        ]);
        $anime = Anime::where('title', 'add_post_title')->first();
        $company1 = Company::where('name', 'add_post_company1')->first();
        $company2 = Company::where('name', 'add_post_company2')->first();
        $company3 = Company::where('name', 'add_post_company3')->first();
        $this->assertDatabaseHas('anime_company', [
            'anime_id' => $anime->id,
            'company_id' => $company1->id,
        ]);
        $this->assertDatabaseHas('anime_company', [
            'anime_id' => $anime->id,
            'company_id' => $company2->id,
        ]);
        $this->assertDatabaseHas('anime_company', [
            'anime_id' => $anime->id,
            'company_id' => $company3->id,
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
            'title' => 'add_post_title',
            'title_short' => 'add_post_title_short',
            'furigana' => 'add_post_furigana',
            'year' => 2040,
            'coor' => 4,
            'number_of_episode' => 13,
            'public_url' => 'https://add_post_public_url',
            'twitter' => 'add_post_twitterId',
            'hash_tag' => 'add_post_hashTag',
            'company1' => 'add_post_company1',
            'company2' => 'add_post_company2',
            'company3' => 'add_post_company3',
            'city_name' => 'add_post_city_name',
            'summary' => 'add_post_summary',
            'd_anime_store_id' => 'add_post_d_anime_store_id',
            'amazon_prime_video_id' => 'add_post_amazon_prime_video_id',
            'unext_id' => 'add_post_unext_id',
            'fod_id' => 'add_post_fod_id',
            'abema_id' => 'add_post_abema_id',
            'disney_plus_id' => 'add_post_disney_plus_id',
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

    /**
     * 声優追加申請ページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testAddCastRequestView()
    {
        $response = $this->get(route('add_cast_request.show'));
        $response->assertStatus(200);
        $response->assertSee('声優の追加申請');
    }

    /**
     * 声優追加申請のテスト
     *
     * @test
     * @return void
     */
    public function testAddCastRequestPost()
    {
        $response = $this->post(route('add_cast_request.post'), [
            'name' => 'add_name',
            'furigana' => 'add_furigana',
            'sex' => 2,
            'birth' => '2000年4月13日',
            'birthplace' => '千葉県',
            'blood_type' => 'AB',
            'office' => 'add_office',
            'url' => 'add_url',
            'twitter' => 'add_twitter',
            'blog' => 'add_blog',
            'blog_url' => 'add_blog_url',
            'remark' => 'add_remark',
        ]);
        $this->assertDatabaseHas('add_casts', [
            'name' => 'add_name',
            'furigana' => 'add_furigana',
            'sex' => 2,
            'birth' => '2000年4月13日',
            'birthplace' => '千葉県',
            'blood_type' => 'AB',
            'office' => 'add_office',
            'url' => 'add_url',
            'twitter' => 'add_twitter',
            'blog' => 'add_blog',
            'blog_url' => 'add_blog_url',
            'remark' => 'add_remark',
        ]);
    }

    /**
     * ゲスト時の声優追加リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testGuestAddCastRequestApprove()
    {
        $response = $this->post(route('add_cast_request.approve', ['add_cast_id' => $this->addCast->id]), [
            'name' => 'add_name',
            'furigana' => 'add_furigana',
            'sex' => 2,
            'birth' => '2000年4月13日',
            'birthplace' => '千葉県',
            'blood_type' => 'AB',
            'office' => 'add_office',
            'url' => 'add_url',
            'twitter' => 'add_twitter',
            'blog' => 'add_blog',
            'blog_url' => 'add_blog_url',
        ]);
        $response->assertStatus(403);
    }

    /**
     * ユーザーログイン時の声優追加リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testUser2LoginAddCastRequestApprove()
    {
        $this->actingAs($this->user2);
        $response = $this->post(route('add_cast_request.approve', ['add_cast_id' => $this->addCast->id]), [
            'name' => 'add_name',
            'furigana' => 'add_furigana',
            'sex' => 2,
            'birth' => '2000年4月13日',
            'birthplace' => '千葉県',
            'blood_type' => 'AB',
            'office' => 'add_office',
            'url' => 'add_url',
            'twitter' => 'add_twitter',
            'blog' => 'add_blog',
            'blog_url' => 'add_blog_url',
        ]);
        $response->assertStatus(403);
    }

    /**
     * ルートユーザーログイン時の声優追加リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testRootLoginAddCastRequestApprove()
    {
        $this->actingAs($this->user1);
        $response = $this->post(route('add_cast_request.approve', ['add_cast_id' => $this->addCast->id]), [
            'name' => 'add_name',
            'furigana' => 'add_furigana',
            'sex' => 2,
            'birth' => '2000年4月13日',
            'birthplace' => '千葉県',
            'blood_type' => 'AB',
            'office' => 'add_office',
            'url' => 'add_url',
            'twitter' => 'add_twitter',
            'blog' => 'add_blog',
            'blog_url' => 'add_blog_url',
        ]);
        $response->assertRedirect(route('modify_request_list.show'));
        $this->assertDatabaseHas('casts', [
            'name' => 'add_name',
            'furigana' => 'add_furigana',
            'sex' => 2,
            'birth' => '2000年4月13日',
            'birthplace' => '千葉県',
            'blood_type' => 'AB',
            'office' => 'add_office',
            'url' => 'add_url',
            'twitter' => 'add_twitter',
            'blog' => 'add_blog',
            'blog_url' => 'add_blog_url',
        ]);
        $this->assertDatabaseHas('add_casts', [
            'id' => $this->addCast->id,
            'name' => 'add_name',
            'furigana' => 'add_furigana',
            'sex' => 2,
            'birth' => '2000年4月13日',
            'birthplace' => '千葉県',
            'blood_type' => 'AB',
            'office' => 'add_office',
            'url' => 'add_url',
            'twitter' => 'add_twitter',
            'blog' => 'add_blog',
            'blog_url' => 'add_blog_url',
            'delete_flag' => 1,
        ]);
    }

    /**
     * ルートユーザーログイン時の声優追加リクエスト時の異常値テスト
     *
     * @test
     * @return void
     */
    public function testRootLoginNotExistAddCastRequestApprove()
    {
        $this->actingAs($this->user1);
        $response = $this->post(route('add_cast_request.approve', ['add_cast_id' => 33333333333333333333]), [
            'name' => 'add_name',
            'furigana' => 'add_furigana',
            'sex' => 2,
            'birth' => '2000年4月13日',
            'birthplace' => '千葉県',
            'blood_type' => 'AB',
            'office' => 'add_office',
            'url' => 'add_url',
            'twitter' => 'add_twitter',
            'blog' => 'add_blog',
            'blog_url' => 'add_blog_url',
        ]);
        $response->assertStatus(404);
    }

    /**
     * ゲスト時の声優追加申請却下リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testGuestAddCastRequestReject()
    {
        $response = $this->get(route('add_cast_request.reject', ['add_cast_id' => $this->addCast->id]));
        $response->assertStatus(403);
    }

    /**
     * ユーザーログイン時の声優追加申請却下リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testUser2LoginAddCastRequestReject()
    {
        $this->actingAs($this->user2);
        $response = $this->get(route('add_cast_request.reject', ['add_cast_id' => $this->addCast->id]));
        $response->assertStatus(403);
    }

    /**
     * ルートユーザーログイン時の声優追加申請却下時のテスト
     *
     * @test
     * @return void
     */
    public function testRootLoginAddCastRequestReject()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('add_cast_request.reject', ['add_cast_id' => $this->addCast->id]));
        $response->assertRedirect(route('modify_request_list.show'));
        $this->assertDatabaseMissing('add_casts', [
            'id' => $this->addCast->id,
        ]);
    }

    /**
     * ルートユーザーログイン時の声優追加申請却下時の異常値テスト
     *
     * @test
     * @return void
     */
    public function testRootLoginNotExistAddCastRequestReject()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('add_cast_request.reject', ['add_cast_id' => 33333333333333333333333]));
        $response->assertStatus(404);
    }

    /**
     * 声優削除申請ページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testDeleteCastRequestView()
    {
        $response = $this->get(route('delete_cast_request.show', ['cast_id' => $this->cast1->id]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->cast1->name,
            '削除事由',
        ]);
    }

    /**
     * 声優削除申請ページの表示の異常値テスト
     *
     * @test
     * @return void
     */
    public function testNotExistDeleteCastRequestView()
    {
        $response = $this->get(route('delete_cast_request.show', ['cast_id' => 3333333333333333]));
        $response->assertStatus(404);
    }

    /**
     * 声優削除申請のテスト
     *
     * @test
     * @return void
     */
    public function testDeleteCastRequestPost()
    {
        $response = $this->post(route('delete_cast_request.post', ['cast_id' => $this->cast1->id,]), [
            'remark' => 'remark_comment',
        ]);
        $this->assertDatabaseHas('delete_casts', [
            'cast_id' => $this->cast1->id,
            'remark' => 'remark_comment',
        ]);
    }

    /**
     * 声優削除申請の異常値テスト
     *
     * @test
     * @return void
     */
    public function testNotExistDeleteCastRequestPost()
    {
        $response = $this->post(route('delete_cast_request.post', ['cast_id' => 3333333333333333333333333]), [
            'remark' => 'remark_comment',
        ]);
        $response->assertStatus(404);
    }

    /**
     * ゲスト時の声優削除リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testGuestDeleteCastRequestApprove()
    {
        $response = $this->post(route('delete_cast_request.approve', ['delete_cast_id' => $this->deleteCast->id]));
        $response->assertStatus(403);
    }

    /**
     * ユーザーログイン時の声優削除リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testUser2LoginDeleteCastRequestApprove()
    {
        $this->actingAs($this->user2);
        $response = $this->post(route('delete_cast_request.approve', ['delete_cast_id' => $this->deleteCast->id]));
        $response->assertStatus(403);
    }

    /**
     * ルートユーザーログイン時の声優削除リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testRootLoginDeleteCastRequestApprove()
    {
        $this->actingAs($this->user1);
        $response = $this->post(route('delete_cast_request.approve', ['delete_cast_id' => $this->deleteCast->id]));
        $response->assertRedirect(route('modify_request_list.show'));
        $this->assertDatabaseMissing('casts', [
            'id' => $this->cast1->id,
        ]);
        $this->assertDatabaseMissing('delete_casts', [
            'id' => $this->deleteCast->id,
        ]);
    }

    /**
     * ルートユーザーログイン時の声優削除リクエスト時の異常値テスト
     *
     * @test
     * @return void
     */
    public function testRootLoginNotExistDeleteCastRequestApprove()
    {
        $this->actingAs($this->user1);
        $response = $this->post(route('delete_cast_request.approve', ['delete_cast_id' => 33333333333333333333]));
        $response->assertStatus(404);
    }

    /**
     * ゲスト時の声優削除申請却下リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testGuestDeleteCastRequestReject()
    {
        $response = $this->get(route('delete_cast_request.reject', ['delete_cast_id' => $this->deleteCast->id]));
        $response->assertStatus(403);
    }

    /**
     * ユーザーログイン時の声優削除申請却下リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testUser2LoginDeleteCastRequestReject()
    {
        $this->actingAs($this->user2);
        $response = $this->get(route('delete_cast_request.reject', ['delete_cast_id' => $this->deleteCast->id]));
        $response->assertStatus(403);
    }

    /**
     * ルートユーザーログイン時の声優削除申請却下時のテスト
     *
     * @test
     * @return void
     */
    public function testRootLoginDeleteCastRequestReject()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('delete_cast_request.reject', ['delete_cast_id' => $this->deleteCast->id]));
        $response->assertRedirect(route('modify_request_list.show'));
        $this->assertDatabaseMissing('delete_casts', [
            'id' => $this->deleteCast->id,
            'cast_id' => $this->cast1->id,
        ]);
    }

    /**
     * ルートユーザーログイン時の声優削除申請却下時の異常値テスト
     *
     * @test
     * @return void
     */
    public function testRootLoginNotExistDeleteCastRequestReject()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('delete_cast_request.reject', ['delete_cast_id' => 33333333333333333333333]));
        $response->assertStatus(404);
    }

    /**
     * 会社削除申請ページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testDeleteCompanyRequestView()
    {
        $response = $this->get(route('delete_company_request.show', ['company_id' => $this->company1->id]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->company1->name,
            '削除事由',
        ]);
    }

    /**
     * 会社削除申請ページの表示の異常値テスト
     *
     * @test
     * @return void
     */
    public function testNotExistDeleteCompanyRequestView()
    {
        $response = $this->get(route('delete_company_request.show', ['company_id' => 3333333333333333]));
        $response->assertStatus(404);
    }

    /**
     * 会社削除申請のテスト
     *
     * @test
     * @return void
     */
    public function testDeleteCompanyRequestPost()
    {
        $response = $this->post(route('delete_company_request.post', ['company_id' => $this->company1->id,]), [
            'remark' => 'remark_comment',
        ]);
        $this->assertDatabaseHas('delete_companies', [
            'company_id' => $this->company1->id,
            'remark' => 'remark_comment',
        ]);
    }

    /**
     * 会社削除申請の異常値テスト
     *
     * @test
     * @return void
     */
    public function testNotExistDeleteCompanyRequestPost()
    {
        $response = $this->post(route('delete_company_request.post', ['company_id' => 3333333333333333333333333]), [
            'remark' => 'remark_comment',
        ]);
        $response->assertStatus(404);
    }

    /**
     * ゲスト時の会社削除リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testGuestDeleteCompanyRequestApprove()
    {
        $response = $this->post(route('delete_company_request.approve', [
            'delete_company_id' => $this->deleteCompany->id
        ]));
        $response->assertStatus(403);
    }

    /**
     * ユーザーログイン時の会社削除リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testUser2LoginDeleteCompanyRequestApprove()
    {
        $this->actingAs($this->user2);
        $response = $this->post(route('delete_company_request.approve', [
            'delete_company_id' => $this->deleteCompany->id
        ]));
        $response->assertStatus(403);
    }

    /**
     * ルートユーザーログイン時の会社削除リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testRootLoginDeleteCompanyRequestApprove()
    {
        $this->actingAs($this->user1);
        $response = $this->post(route('delete_company_request.approve', [
            'delete_company_id' => $this->deleteCompany->id
        ]));
        $response->assertRedirect(route('modify_request_list.show'));
        $this->assertDatabaseMissing('companies', [
            'id' => $this->company1->id,
        ]);
        $this->assertDatabaseMissing('delete_companies', [
            'id' => $this->deleteCompany->id,
        ]);
    }

    /**
     * ルートユーザーログイン時の会社削除リクエスト時の異常値テスト
     *
     * @test
     * @return void
     */
    public function testRootLoginNotExistDeleteCompanyRequestApprove()
    {
        $this->actingAs($this->user1);
        $response = $this->post(route('delete_company_request.approve', [
            'delete_company_id' => 33333333333333333333
        ]));
        $response->assertStatus(404);
    }

    /**
     * ゲスト時の会社削除申請却下リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testGuestDeleteCompanyRequestReject()
    {
        $response = $this->get(route('delete_company_request.reject', [
            'delete_company_id' => $this->deleteCompany->id
        ]));
        $response->assertStatus(403);
    }

    /**
     * ユーザーログイン時の会社削除申請却下リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testUser2LoginDeleteCompanyRequestReject()
    {
        $this->actingAs($this->user2);
        $response = $this->get(route('delete_company_request.reject', [
            'delete_company_id' => $this->deleteCompany->id
        ]));
        $response->assertStatus(403);
    }

    /**
     * ルートユーザーログイン時の会社削除申請却下時のテスト
     *
     * @test
     * @return void
     */
    public function testRootLoginDeleteCompanyRequestReject()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('delete_company_request.reject', [
            'delete_company_id' => $this->deleteCompany->id
        ]));
        $response->assertRedirect(route('modify_request_list.show'));
        $this->assertDatabaseMissing('delete_companies', [
            'id' => $this->deleteCompany->id,
            'company_id' => $this->company1->id,
        ]);
    }

    /**
     * ルートユーザーログイン時の会社削除申請却下時の異常値テスト
     *
     * @test
     * @return void
     */
    public function testRootLoginNotExistDeleteCompanyRequestReject()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('delete_company_request.reject', [
            'delete_company_id' => 33333333333333333333333
        ]));
        $response->assertStatus(404);
    }

    /**
     * 作品の追加履歴の表示のテスト
     *
     * @test
     * @return void
     */
    public function testAddAnimeLogView()
    {
        $response = $this->get(route('add_anime_log.show'));
        $response->assertStatus(200);
        $response->assertSee($this->addAnimeDeleted->title);
        $response->assertDontSee($this->addAnime->title);
    }

    /**
     * 声優の追加履歴の表示のテスト
     *
     * @test
     * @return void
     */
    public function testAddCastLogView()
    {
        $response = $this->get(route('add_cast_log.show'));
        $response->assertStatus(200);
        $response->assertSee($this->addCastDeleted->name);
        $response->assertDontSee($this->addCast->name);
    }
}
