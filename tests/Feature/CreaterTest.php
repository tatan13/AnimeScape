<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Creater;
use App\Models\User;
use App\Models\Anime;
use App\Models\Company;
use App\Models\UserLikeCreater;
use Tests\TestCase;

class CreaterTest extends TestCase
{
    use RefreshDatabase;

    private Creater $creater;
    private User $user1;
    private User $user2;
    private Anime $anime1;
    private Anime $anime2;
    private company $company;

    protected function setUp(): void
    {
        parent::setUp();
        $this->anime1 = Anime::factory()->create([
            'median' => 80,
            'count' => 332,
        ]);
        $this->anime2 = Anime::factory()->create([
            'median' => 78,
            'count' => 232,
        ]);

        $this->creater = Creater::factory()->create();

        $this->user1 = User::factory()->create();
        $this->user2 = User::factory()->create();

        $this->user2->likeCreaters()->attach($this->creater->id);

        $this->anime1->creaters()->attach($this->creater->id);
        $this->anime2->creaters()->attach($this->creater->id);

        $this->company = Company::factory()->create();
        $this->anime1->companies()->attach($this->company->id);
    }

    /**
     * クリエイターページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testCreaterView()
    {
        $response = $this->get(route('creater.show', ['creater_id' => $this->creater->id]));
        $response->assertStatus(200);
    }

    /**
     * クリエイターページのプロフィールの表示のテスト
     *
     * @test
     * @return void
     */
    public function testCreaterProfileView()
    {
        $response = $this->get(route('creater.show', ['creater_id' => $this->creater->id]));
        $response->assertSeeInOrder([
            $this->creater->name,
            $this->creater->furigana,
            $this->creater->sex_label,
            $this->creater->birth,
            $this->creater->birthplace,
            $this->creater->blood_type,
            $this->creater->twitter,
            $this->creater->blog,
        ]);
    }

    /**
     * クリエイターページの情報の表示のテスト
     *
     * @test
     * @return void
     */
    public function testCreaterInformationView()
    {
        $response = $this->get(route('creater.show', ['creater_id' => $this->creater->id]));
        $response->assertSeeInOrder([
            $this->creater->name,
            '計2本',
            $this->anime1->title,
            $this->anime1->companies[0]->name,
            $this->anime1->year,
            $this->anime1->coor_label,
            $this->anime1->median,
            $this->anime1->count,
            $this->anime2->title,
            $this->anime2->year,
            $this->anime2->coor_label,
            $this->anime2->median,
            $this->anime2->count,
        ]);
    }

    /**
     * ゲスト時のクリエイターページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testGuestCreaterView()
    {
        $response = $this->get(route('creater.show', ['creater_id' => $this->creater->id]));
        $response->assertDontSee('お気に入り');
        $response->assertDontSee('つけた得点');
    }

    /**
     * ログイン時のクリエイターページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testUser1LoginView()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('creater.show', ['creater_id' => $this->creater->id]));
        $response->assertSee('つけた得点');
    }

    /**
     * 存在しないクリエイターページにアクセスしたときのテスト
     *
     * @test
     * @return void
     */
    public function testNotExistCreaterView()
    {
        $response = $this->get(route('creater.show', ['creater_id' => 33333333333333]));
        $response->assertStatus(404);
    }

    /**
     * お気に入り登録していないクリエイターのお気に入り登録のテスト
     *
     * @test
     * @return void
     */
    public function testUser1LoginCreaterLike()
    {
        $this->actingAs($this->user1);
        $this->get(route('creater.like', ['creater_id' => $this->creater->id]));
        $this->assertDatabaseHas('user_like_creaters', [
            'id' => 2,
            'user_id' => $this->user1->id,
            'creater_id' => $this->creater->id,
        ]);
    }

    /**
     * お気に入り登録しているクリエイターのお気に入り登録のテスト
     *
     * @test
     * @return void
     */
    public function testUser2LoginCreaterLike()
    {
        $this->actingAs($this->user2);
        $this->get(route('creater.like', ['creater_id' => $this->creater->id]));
        $this->assertDatabaseMissing('user_like_creaters', [
            'id' => 3,
            'user_id' => $this->user1->id,
            'creater_id' => $this->creater->id,
        ]);
    }

    /**
     * お気に入り登録しているクリエイターのお気に入り解除のテスト
     *
     * @test
     * @return void
     */
    public function testUser2LoginCreaterUnlike()
    {
        $this->actingAs($this->user2);
        $this->get(route('creater.unlike', ['creater_id' => $this->creater->id]));
        $this->assertDatabaseMissing('user_like_creaters', [
            'id' => 1,
            'user_id' => $this->user2->id,
            'creater_id' => $this->creater->id,
        ]);
    }

    /**
     * クリエイターのお気に入り登録の異常値テスト
     *
     * @test
     * @return void
     */
    public function testUser1LoginNotExistCreaterLike()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('creater.like', ['creater_id' => 333333333333333333333]));
        $response->assertStatus(404);
    }

    /**
     * クリエイターのお気に入り解除のテスト
     *
     * @test
     * @return void
     */
    public function testUser1LoginNotExistCreaterUnlike()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('creater.unlike', ['creater_id' => 3333333333333333333333333333]));
        $response->assertStatus(404);
    }

    /**
     * ゲスト時のクリエイターのお気に入り登録時リダイレクトテスト
     *
     * @test
     * @return void
     */
    public function testGuestCreaterLike()
    {
        $response = $this->get((route('creater.like', ['creater_id' => $this->creater->id])));
        $response->assertRedirect(route('login'));
    }

    /**
     * ゲスト時のクリエイターのお気に入り解除時リダイレクトテスト
     *
     * @test
     * @return void
     */
    public function testGuestCreaterUnlike()
    {
        $response = $this->get(route('creater.unlike', ['creater_id' => $this->creater->id]));
        $response->assertRedirect(route('login'));
    }

    /**
     * クリエイターリストの表示のテスト
     *
     * @test
     * @return void
     */
    public function testCreaterListView()
    {
        $response = $this->get(route('creater_list.show'));
        $response->assertStatus(200);
    }
}
