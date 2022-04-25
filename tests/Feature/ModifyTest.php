<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\ModifyAnime;
use App\Models\ModifyOccupation;
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
        $this->anime->modifyOccupations()->create(['cast_name' => $this->cast1->name]);
        $this->anime->modifyOccupations()->create(['cast_name' => 'modify_cast1']);
        $this->anime1->modifyOccupations()->create(['cast_name' => 'modify_cast1']);
        $this->anime1->modifyOccupations()->create(['cast_name' => 'modify_cast2']);
        $this->anime->actCasts()->attach($this->cast1->id);
        $this->anime->actCasts()->attach($this->cast2->id);

        $this->user1 = User::factory()->create(['uid' => 'root']);
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
        $response->assertSeeInOrder([
            $this->anime->title,
            $this->anime->title_short,
            $this->anime->year,
            $this->anime->coor_label,
            $this->anime->public_url,
            $this->anime->twitter,
            $this->anime->hash_tag,
            $this->anime->company,
            $this->anime->city_name,
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
            'title_short' => 'modify_title_short',
            'year' => 2040,
            'coor' => 4,
            'public_url' => 'https://modify_public_url',
            'twitter' => 'modify_twitterId',
            'hash_tag' => 'modify_hashTag',
            'company' => 'modify_company',
            'city_name' => 'modify_city_name',
        ]);
        $response->assertRedirect("/modify/anime/{$this->anime->id}");
        $this->assertDatabaseHas('modify_animes', [
            'id' => 3,
            'anime_id' => $this->anime->id,
            'title' => 'modify_title',
            'title_short' => 'modify_title_short',
            'year' => 2040,
            'coor' => 4,
            'public_url' => 'https://modify_public_url',
            'twitter' => 'modify_twitterId',
            'hash_tag' => 'modify_hashTag',
            'company' => 'modify_company',
            'city_name' => 'modify_city_name',
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
     * ゲスト時のアニメの情報修正申請のリストページリクエスト時のリダイレクトテスト
     *
     * @test
     * @return void
     */
    public function testGuestModifyListView()
    {
        $response = $this->get('/modify_list');
        $response->assertRedirect('/login');
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
        $response->assertStatus(404);
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
        $response->assertSeeInOrder([
            '1件目',
            'modify_title',
            'modify_title_short',
            2040,
            'https://modify_public_url',
            'modify_twitterId',
            'modify_hashTag',
            'modify_company',
            'modify_city_name',
            '2件目',
            $this->modifyAnime1->title,
            $this->modifyAnime1->title_short,
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
        $response->assertRedirect("/login");
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
            'title_short' => 'modify_title_short',
            'year' => 2040,
            'coor' => 4,
            'public_url' => 'https://modify_public_url',
            'twitter' => 'modify_twitterId',
            'hash_tag' => 'modify_hashTag',
            'company' => 'modify_company',
            'city_name' => 'modify_city_name',
        ]);
        $response->assertStatus(404);
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
            'title_short' => 'modify_title_short',
            'year' => 2040,
            'coor' => 4,
            'public_url' => 'https://modify_public_url',
            'twitter' => 'modify_twitterId',
            'hash_tag' => 'modify_hashTag',
            'company' => 'modify_company',
            'city_name' => 'modify_city_name',
        ]);
        $response->assertRedirect('/modify_list');
        $this->assertDatabaseHas('animes', [
            'id' => $this->anime->id,
            'title' => 'modify_title',
            'title_short' => 'modify_title_short',
            'year' => 2040,
            'coor' => 4,
            'public_url' => 'https://modify_public_url',
            'twitter' => 'modify_twitterId',
            'hash_tag' => 'modify_hashTag',
            'company' => 'modify_company',
            'city_name' => 'modify_city_name',
        ]);
        $this->assertDatabaseMissing('modify_animes', [
            'id' => $this->modifyAnime->id,
            'anime_id' => $this->anime->id,
            'title' => 'modify_title',
            'title_short' => 'modify_title_short',
            'year' => 2040,
            'coor' => 4,
            'public_url' => 'https://modify_public_url',
            'twitter' => 'modify_twitterId',
            'hash_tag' => 'modify_hashTag',
            'company' => 'modify_company',
            'city_name' => 'modify_city_name',
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
        $response->assertRedirect("/login");
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
        $response->assertStatus(404);
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
            'title_short' => 'modify_title_short',
            'year' => 2040,
            'coor' => 4,
            'public_url' => 'https://modify_public_url',
            'twitter' => 'modify_twitterId',
            'hash_tag' => 'modify_hashTag',
            'company' => 'modify_company',
            'city_name' => 'modify_city_name',
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
        $response->assertRedirect("/login");
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
        $response->assertStatus(404);
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
        $response->assertRedirect("/login");
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
        $response->assertStatus(404);
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
}
