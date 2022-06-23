<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Anime;
use App\Models\Cast;
use App\Models\Creater;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    private Anime $anime1;
    private Anime $anime2;
    private Anime $anime3;
    private Cast $cast1;
    private Cast $cast2;
    private Cast $cast3;
    private Creater $creater1;
    private Creater $creater2;
    private Creater $creater3;
    private User $user1;
    private User $user2;
    private User $user3;

    protected function setUp(): void
    {
        parent::setUp();
        $this->anime1 = Anime::factory()->create(['title' => '霊剣山 星屑たちの宴']);
        $this->anime2 = Anime::factory()->create(['title' => '霊剣山 叡智への資格']);
        $this->anime3 = Anime::factory()->create(['title' => 'animeName']);
        $this->cast1 = Cast::factory()->create(['name' => 'castName1']);
        $this->cast2 = Cast::factory()->create(['name' => 'castName2']);
        $this->cast3 = Cast::factory()->create(['name' => 'cname']);
        $this->creater1 = Creater::factory()->create(['name' => 'createrName1']);
        $this->creater2 = Creater::factory()->create(['name' => 'createrName2']);
        $this->creater3 = Creater::factory()->create(['name' => 'cname']);
        $this->user1 = User::factory()->create(['name' => 'userName1']);
        $this->user2 = User::factory()->create(['name' => 'userName2']);
        $this->user3 = User::factory()->create(['name' => 'uname']);
    }

    /**
     * ゲスト時のアニメ検索のテスト
     *
     * @test
     * @return void
     */
    public function testGuestSearchAnimeView()
    {
        $response = $this->get(route('search.show', [
            'category' => 'anime',
            'search_word' => '霊剣山',
        ]));
        $response->assertStatus(200);
        $response->assertDontSee('つけた得点');
    }

    /**
     * ログイン時のアニメ検索のテスト
     *
     * @test
     * @return void
     */
    public function testLoginSearchAnimeView()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('search.show', [
            'category' => 'anime',
            'search_word' => '霊剣山',
        ]));
        $response->assertSee('つけた得点');
    }

    /**
     * 複数のアニメ検索のテスト
     *
     * @test
     * @return void
     */
    public function testSearchSomeAnimeView()
    {
        $response = $this->get(route('search.show', [
            'category' => 'anime',
            'search_word' => '霊剣山',
        ]));
        $response->assertSeeInOrder([$this->anime1->title, $this->anime2->title,]);
        $response->assertDontSee($this->anime3->title);
    }

    /**
     * アニメ検索に空文字を入力した場合のテスト
     *
     * @test
     * @return void
     */
    public function testSearchNullWordAnimeView()
    {
        $response = $this->get(route('search.show', [
            'category' => 'anime',
            'search_word' => '',
        ]));
        $response->assertStatus(200);
    }

    /**
     * 検索に該当するアニメがない場合のテスト
     *
     * @test
     * @return void
     */
    public function testSearchNoAnimeView()
    {
        $response = $this->get(route('search.show', [
            'category' => 'anime',
            'search_word' => 'not found',
        ]));
        $response->assertStatus(200);
        $response->assertSee('該当するアニメがありませんでした。');
    }
















    /**
     * ゲスト時の声優検索の表示のテスト
     *
     * @test
     * @return void
     */
    public function testGuestSearchCastView()
    {
        $response = $this->get(route('search.show', [
            'category' => 'cast',
            'search_word' => 'castName',
        ]));
        $response->assertStatus(200);
        $response->assertDontSee('つけた得点');
    }

    /**
     * ログイン時の声優検索の表示のテスト
     *
     * @test
     * @return void
     */
    public function testLoginSearchCastView()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('search.show', [
            'category' => 'cast',
            'search_word' => 'castName',
        ]));
        $response->assertSee('つけた得点');
    }

    /**
     * 複数の声優検索のテスト
     *
     * @test
     * @return void
     */
    public function testSearchSomeCastView()
    {
        $response = $this->get(route('search.show', [
            'category' => 'cast',
            'search_word' => 'castName',
        ]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([$this->cast1->name, $this->cast2->name]);
        $response->assertDontSee($this->cast3->name);
    }

    /**
     * 声優検索に空文字を入力した場合のテスト
     *
     * @test
     * @return void
     */
    public function testSearchNullWordCastView()
    {
        $response = $this->get(route('search.show', [
            'category' => 'cast',
            'search_word' => '',
        ]));
        $response->assertStatus(200);
    }

    /**
     * 検索に該当する声優がいない場合のテスト
     *
     * @test
     * @return void
     */
    public function testSearchNoCastView()
    {
        $response = $this->get(route('search.show', [
            'category' => 'cast',
            'search_word' => 'not found',
        ]));
        $response->assertStatus(200);
        $response->assertSee('該当する声優がいませんでした。');
    }

    /**
     * ゲスト時のクリエイター検索の表示のテスト
     *
     * @test
     * @return void
     */
    public function testGuestSearchCreaterView()
    {
        $response = $this->get(route('search.show', [
            'category' => 'creater',
            'search_word' => 'createrName',
        ]));
        $response->assertStatus(200);
        $response->assertDontSee('つけた得点');
    }

    /**
     * ログイン時のクリエイター検索の表示のテスト
     *
     * @test
     * @return void
     */
    public function testLoginSearchCreaterView()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('search.show', [
            'category' => 'creater',
            'search_word' => 'createrName',
        ]));
        $response->assertSee('つけた得点');
    }

    /**
     * 複数のクリエイター検索のテスト
     *
     * @test
     * @return void
     */
    public function testSearchSomeCreaterView()
    {
        $response = $this->get(route('search.show', [
            'category' => 'creater',
            'search_word' => 'createrName',
        ]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([$this->creater1->name, $this->creater2->name]);
        $response->assertDontSee($this->creater3->name);
    }

    /**
     * クリエイター検索に空文字を入力した場合のテスト
     *
     * @test
     * @return void
     */
    public function testSearchNullWordCreaterView()
    {
        $response = $this->get(route('search.show', [
            'category' => 'creater',
            'search_word' => '',
        ]));
        $response->assertStatus(200);
    }

    /**
     * 検索に該当するクリエイターがいない場合のテスト
     *
     * @test
     * @return void
     */
    public function testSearchNoCreaterView()
    {
        $response = $this->get(route('search.show', [
            'category' => 'creater',
            'search_word' => 'not found',
        ]));
        $response->assertStatus(200);
        $response->assertSee('該当するクリエイターがいませんでした。');
    }

    /**
     * 複数のユーザー検索のテスト
     *
     * @test
     * @return void
     */
    public function testSearchSomeUserView()
    {
        $response = $this->get(route('search.show', [
            'category' => 'user',
            'search_word' => 'userName',
        ]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([$this->user1->name, $this->user2->name]);
        $response->assertDontSee($this->user3->name);
    }

    /**
     * ユーザー検索に空文字を入力した場合のテスト
     *
     * @test
     * @return void
     */
    public function testSearchNullWordUserView()
    {
        $response = $this->get(route('search.show', [
            'category' => 'user',
            'search_word' => '',
        ]));
        $response->assertStatus(200);
    }

    /**
     * 検索に該当するユーザーがいない場合のテスト
     *
     * @test
     * @return void
     */
    public function testSearchNoUserView()
    {
        $response = $this->get(route('search.show', [
            'category' => 'user',
            'search_word' => 'not found',
        ]));
        $response->assertStatus(200);
        $response->assertSee('該当するユーザーがいませんでした。');
    }

    /**
     * 不正なカテゴリー検索をした場合のテスト
     *
     * @test
     * @return void
     */
    public function testSearchExceptionCategory()
    {
        $response = $this->get(route('search.show', [
            'category' => 'ExceptionCategory',
            'search_word' => 'not found',
        ]));
        $response->assertstatus(404);
    }
}
