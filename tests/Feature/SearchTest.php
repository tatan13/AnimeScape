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

    private Anime $anime1;
    private Anime $anime2;
    private Anime $anime3;
    private Cast $cast1;
    private Cast $cast2;
    private Cast $cast3;
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
        $this->user1 = User::factory()->create(['uid' => 'userName1']);
        $this->user2 = User::factory()->create(['uid' => 'userName2']);
        $this->user3 = User::factory()->create(['uid' => 'uname']);
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
        $response->assertSee('検索キーワードを入力してください。');
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
        $response->assertSee('該当するアニメがありませんでした。');
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
        $response->assertSee('検索キーワードを入力してください。');
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
        $response->assertSee('該当する声優がいませんでした。');
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
        $response->assertSeeInOrder([$this->user1->uid, $this->user2->uid]);
        $response->assertDontSee($this->user3->uid);
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
        $response->assertSee('検索キーワードを入力してください。');
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
