<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Anime;
use App\Models\Cast;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    private Anime $anime;
    private User $user;
    private Cast $cast;

    protected function setUp(): void
    {
        parent::setUp();
        $this->anime = new Anime();
        $this->anime->title = '霊剣山 星屑たちの宴';
        $this->anime->title_short = '霊剣山 星屑たちの宴';
        $this->anime->year = 2022;
        $this->anime->coor = 1;
        $this->anime->save();

        $this->cast = new Cast();
        $this->cast->name = 'castname';
        $this->cast->save();

        $this->user = User::factory()->create();
    }

    /**
     * アニメの検索ページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testSearchAnimeView()
    {
        $response = $this->get(route('search', [
            'category' => 'anime',
            'search_word' => '霊剣山',
        ]));

        $response->assertStatus(200);
        $response->assertSee('霊剣山 星屑たちの宴');

        $this->get(route('search', [
            'category' => 'anime',
            'search_word' => '',
        ]))->assertSee('検索キーワードを入力してください。');

        $this->get(route('search', [
            'category' => 'anime',
            'search_word' => 'not found',
        ]))->assertSee('該当するアニメがありませんでした。');

        $this->get(route('search', [
            'category' => 'anim',
            'search_word' => 'not found',
        ]))->assertSee('不正なアクセスです。');
    }

    /**
     * 声優の検索ページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testSearchCastView()
    {
        $response = $this->get(route('search', [
            'category' => 'cast',
            'search_word' => 'cast',
        ]));

        $response->assertStatus(200);
        $response->assertSee('castname');

        $this->get(route('search', [
            'category' => 'cast',
            'search_word' => '',
        ]))->assertSee('検索キーワードを入力してください。');

        $this->get(route('search', [
            'category' => 'cast',
            'search_word' => 'not found',
        ]))->assertSee('該当する声優がいませんでした。');
    }

    /**
     * ユーザーの検索ページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testSearchUserView()
    {
        $response = $this->get(route('search', [
            'category' => 'user',
            'search_word' => $this->user->uid,
        ]));

        $response->assertStatus(200);
        $response->assertSee($this->user->uid);

        $this->get(route('search', [
            'category' => 'user',
            'search_word' => '',
        ]))->assertSee('検索キーワードを入力してください。');

        $this->get(route('search', [
            'category' => 'user',
            'search_word' => 'not found',
        ]))->assertSee('該当するユーザーがいませんでした。');
    }
}
