<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\ModifyAnime;
use App\Models\ModifyOccupation;
use App\Models\ModifyCast;
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
        $response = $this->get("/modify/anime/{$this->anime->id}");
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
     * アニメの基本情報変更申請のテスト
     *
     * @test
     * @return void
     */
    public function testModifyAnimePost()
    {
        $response = $this->post("/modify/anime/{$this->anime->id}", [
            'title' => 'modify_title',
            'year' => 2040,
            'coor' => 4,
            'public_url' => 'https://modify_public_url',
            'twitter' => 'modify_twitterId',
            'hash_tag' => 'modify_hashTag',
            'company' => 'modify_company',
        ]);
        $response->assertRedirect("/modify/anime/{$this->anime->id}");
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
     * アニメの出演声優情報修正申請ページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testModifyOccupationView()
    {
        $response = $this->get("/modify/occupation/{$this->anime->id}");
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->cast1->name,
            $this->cast2->name,
        ]);
    }

    /**
     * アニメの出演声優情報修正申請のテスト
     *
     * @test
     * @return void
     */
    public function testModifyOccupationPost()
    {
        $response = $this->post("/modify/occupation/{$this->anime->id}", [
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
     * 声優情報修正申請ページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testModifyCastView()
    {
        $response = $this->get("/modify/cast/{$this->cast1->id}");
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
     * 声優情報修正申請のテスト
     *
     * @test
     * @return void
     */
    public function testModifyCastPost()
    {
        $response = $this->post("/modify/cast/{$this->cast1->id}", [
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
     * ゲスト時のアニメの情報修正申請のリストページリクエスト時のリダイレクトテスト
     *
     * @test
     * @return void
     */
    public function testGuestModifyListView()
    {
        $response = $this->get('/modify_list');
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
        $response = $this->get('/modify_list');
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
        $response = $this->get('/modify_list');
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
    public function testGuestModifyAnimeUpdate()
    {
        $response = $this->post("/modify/anime/{$this->modifyAnime->id}/update");
        $response->assertStatus(403);
    }

    /**
     * ユーザーログイン時のアニメの情報更新リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testUser2LoginModifyAnimeUpdate()
    {
        $this->actingAs($this->user2);
        $response = $this->post("/modify/anime/{$this->modifyAnime->id}/update", [
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
    public function testRootLoginModifyAnimeUpdate()
    {
        $this->actingAs($this->user1);
        $response = $this->post("/modify/anime/{$this->modifyAnime->id}/update", [
            'title' => 'modify_title',
            'year' => 2040,
            'coor' => 4,
            'public_url' => 'https://modify_public_url',
            'twitter' => 'modify_twitterId',
            'hash_tag' => 'modify_hashTag',
            'company' => 'modify_company',
        ]);
        $response->assertRedirect('/modify_list');
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
     * ゲスト時のアニメの情報修正申請却下リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testGuestModifyAnimeDelete()
    {
        $response = $this->get("/modify/anime/{$this->modifyAnime->id}/delete");
        $response->assertStatus(403);
    }

    /**
     * ユーザーログイン時のアニメの情報修正申請却下リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testUser2LoginModifyAnimeDelete()
    {
        $this->actingAs($this->user2);
        $response = $this->get("/modify/anime/{$this->modifyAnime->id}/delete");
        $response->assertStatus(403);
    }

    /**
     * ルートユーザーログイン時のアニメの情報修正申請却下時のテスト
     *
     * @test
     * @return void
     */
    public function testRootLoginModifyAnimeDelete()
    {
        $this->actingAs($this->user1);
        $response = $this->get("/modify/anime/{$this->modifyAnime->id}/delete");
        $response->assertRedirect('/modify_list');
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
     * ゲスト時のアニメの出演声優更新リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testGuestModifyOccupationUpdate()
    {
        $response = $this->post("/modify/occupation/{$this->anime->id}/update");
        $response->assertStatus(403);
    }

    /**
     * ユーザーログイン時のアニメの出演声優更新リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testUser2LoginModifyOccupationUpdate()
    {
        $this->actingAs($this->user2);
        $response = $this->post("/modify/occupation/{$this->anime->id}/update", [
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
    public function testRootLoginModifyOccupationUpdate()
    {
        $this->actingAs($this->user1);
        $response = $this->post("/modify/occupation/{$this->anime->id}/update", [
            $this->cast1->name,
            'modify_cast1',
        ]);
        $response->assertRedirect('/modify_list');
        $this->assertDatabaseHas('occupations', [
            'anime_id' => $this->anime->id,
            'cast_id' => 1,
        ]);
        $this->assertDatabaseHas('occupations', [
            'anime_id' => $this->anime->id,
            'cast_id' => 3,
        ]);
        $this->assertDatabaseHas('casts', [
            'id' => 3,
            'name' => 'modify_cast1',
        ]);
        $this->assertDatabaseMissing('occupations', [
            'anime_id' => $this->anime->id,
            'cast_id' => 2,
        ]);
        $this->assertDatabaseMissing('modify_occupations', [
            'anime_id' => $this->anime->id,
        ]);
    }

    /**
     * ゲスト時のアニメの出演声優却下リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testGuestModifyOccupationDelete()
    {
        $response = $this->get("/modify/occupation/{$this->anime->id}/delete");
        $response->assertStatus(403);
    }

    /**
     * ユーザーログイン時のアニメの出演声優却下リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testUser2LoginModifyOccupationDelete()
    {
        $this->actingAs($this->user2);
        $response = $this->get("/modify/occupation/{$this->anime->id}/delete");
        $response->assertStatus(403);
    }

    /**
     * ルートユーザーログイン時のアニメの出演声優却下リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testRootLoginModifyOccupationDelete()
    {
        $this->actingAs($this->user1);
        $response = $this->get("/modify/occupation/{$this->anime->id}/delete");
        $response->assertRedirect('/modify_list');
        $this->assertDatabaseMissing('modify_occupations', [
            'anime_id' => $this->anime->id,
        ]);
    }


















    /**
     * ゲスト時の声優情報更新リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testGuestModifyCastUpdate()
    {
        $response = $this->post("/modify/cast/{$this->modifyCast1->id}/update");
        $response->assertStatus(403);
    }

    /**
     * ユーザーログイン時の声優情報更新リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testUser2LoginModifyCastUpdate()
    {
        $this->actingAs($this->user2);
        $response = $this->post("/modify/cast/{$this->modifyCast1->id}/update", [
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
    public function testRootLoginModifyCastUpdate()
    {
        $this->actingAs($this->user1);
        $response = $this->post("/modify/cast/{$this->modifyCast1->id}/update", [
            'name' => 'modify_name',
            'furigana' => 'modify_furigana',
            'sex' => 2,
            'office' => 'modify_office',
            'url' => 'modify_url',
            'twitter' => 'modify_twitter',
            'blog' => 'modify_blog',
        ]);
        $response->assertRedirect('/modify_list');
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
     * ゲスト時の声優情報修正申請却下リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testGuestModifyCastDelete()
    {
        $response = $this->get("/modify/cast/{$this->modifyCast1->id}/delete");
        $response->assertStatus(403);
    }

    /**
     * ユーザーログイン時の声優情報修正申請却下リクエスト時のテスト
     *
     * @test
     * @return void
     */
    public function testUser2LoginModifyCastDelete()
    {
        $this->actingAs($this->user2);
        $response = $this->get("/modify/cast/{$this->modifyCast1->id}/delete");
        $response->assertStatus(403);
    }

    /**
     * ルートユーザーログイン時の声優情報修正申請却下時のテスト
     *
     * @test
     * @return void
     */
    public function testRootLoginModifyCastDelete()
    {
        $this->actingAs($this->user1);
        $response = $this->get("/modify/cast/{$this->modifyCast1->id}/delete");
        $response->assertRedirect('/modify_list');
        $this->assertDatabaseMissing('modify_casts', [
            'id' => $this->modifyCast1->id,
            'cast_id' => $this->cast1->id,
        ]);
    }
}
