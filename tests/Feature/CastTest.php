<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Cast;
use App\Models\User;
use App\Models\Anime;
use App\Models\Company;
use App\Models\UserLikeCast;
use Tests\TestCase;

class CastTest extends TestCase
{
    use RefreshDatabase;

    private Cast $cast;
    private User $user1;
    private User $user2;
    private User $user3;
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

        $this->cast = Cast::factory()->create();

        $this->user1 = User::factory()->create();
        $this->user2 = User::factory()->create();
        $this->user3 = User::factory()->create();

        $this->user2->likeCasts()->attach($this->cast->id);
        $this->user3->likeCasts()->attach($this->cast->id);

        $this->anime1->actCasts()->attach($this->cast->id);
        $this->anime2->actCasts()->attach($this->cast->id);

        $this->company = Company::factory()->create();
        $this->anime1->companies()->attach($this->company->id);

        $this->anime1->reviewUsers()->attach($this->user2->id, ['score' => 100, 'watch' => 1]);
        $this->anime2->reviewUsers()->attach($this->user2->id, ['score' => 90, 'watch' => 1]);
        $this->anime1->reviewUsers()->attach($this->user3->id, ['score' => 80, 'watch' => 1]);
    }

    /**
     * 声優ページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testCastView()
    {
        $response = $this->get(route('cast.show', ['cast_id' => $this->cast->id]));
        $response->assertStatus(200);
    }

    /**
     * 声優ページのプロフィールの表示のテスト
     *
     * @test
     * @return void
     */
    public function testCastProfileView()
    {
        $response = $this->get(route('cast.show', ['cast_id' => $this->cast->id]));
        $response->assertSeeInOrder([
            $this->cast->name,
            $this->cast->furigana,
            $this->cast->sex_label,
            $this->cast->birth,
            $this->cast->birthplace,
            $this->cast->blood_type,
            $this->cast->office,
            $this->cast->twitter,
            $this->cast->blog,
        ]);
    }

    /**
     * 声優ページの情報の表示のテスト
     *
     * @test
     * @return void
     */
    public function testCastInformationView()
    {
        $response = $this->get(route('cast.show', ['cast_id' => $this->cast->id]));
        $response->assertSeeInOrder([
            $this->cast->name,
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
     * 声優ページの統計情報の表示のテスト
     *
     * @test
     * @return void
     */
    public function testCastStatisticsView()
    {
        $response = $this->get(route('cast.show', ['cast_id' => $this->cast->id]));
        $response->assertSeeInOrder([
            '中央値',
            90,
            '平均値',
            90,
            '総得点数',
            3,
            'ユーザー数',
            2,
            '被お気に入りユーザー数',
            2
        ]);
    }

    /**
     * 声優ページのお気に入りユーザーしているユーザーの表示のテスト
     *
     * @test
     * @return void
     */
    public function testCastLikedUsersView()
    {
        $response = $this->get(route('cast.show', ['cast_id' => $this->cast->id]));
        $response->assertSeeInOrder([
            $this->user2->name,
            '2本',
            '100%',
            $this->user3->name,
            '1本',
            '50%',
        ]);
    }

    /**
     * ゲスト時の声優ページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testGuestCastView()
    {
        $response = $this->get(route('cast.show', ['cast_id' => $this->cast->id]));
        $response->assertDontSee('お気に入り声優として登録する');
        $response->assertDontSee('つけた得点');
    }

    /**
     * ログイン時の声優ページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testUser1LoginView()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('cast.show', ['cast_id' => $this->cast->id]));
        $response->assertSee('つけた得点');
    }

    /**
     * 存在しない声優ページにアクセスしたときのテスト
     *
     * @test
     * @return void
     */
    public function testNotExistCastView()
    {
        $response = $this->get(route('cast.show', ['cast_id' => 33333333333333]));
        $response->assertStatus(404);
    }

    /**
     * お気に入り登録していない声優のお気に入り登録のテスト
     *
     * @test
     * @return void
     */
    public function testUser1LoginCastLike()
    {
        $this->actingAs($this->user1);
        $this->get(route('cast.like', ['cast_id' => $this->cast->id]));
        $this->assertDatabaseHas('user_like_casts', [
            'id' => 3,
            'user_id' => $this->user1->id,
            'cast_id' => $this->cast->id,
        ]);
    }

    /**
     * お気に入り登録している声優のお気に入り登録のテスト
     *
     * @test
     * @return void
     */
    public function testUser2LoginCastLike()
    {
        $this->actingAs($this->user2);
        $this->get(route('cast.like', ['cast_id' => $this->cast->id]));
        $this->assertDatabaseMissing('user_like_casts', [
            'id' => 3,
            'user_id' => $this->user1->id,
            'cast_id' => $this->cast->id,
        ]);
    }

    /**
     * お気に入り登録している声優のお気に入り解除のテスト
     *
     * @test
     * @return void
     */
    public function testUser2LoginCastUnlike()
    {
        $this->actingAs($this->user2);
        $this->get(route('cast.unlike', ['cast_id' => $this->cast->id]));
        $this->assertDatabaseMissing('user_like_casts', [
            'id' => 1,
            'user_id' => $this->user2->id,
            'cast_id' => $this->cast->id,
        ]);
    }

    /**
     * 声優のお気に入り登録の異常値テスト
     *
     * @test
     * @return void
     */
    public function testUser1LoginNotExistCastLike()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('cast.like', ['cast_id' => 333333333333333333333]));
        $response->assertStatus(404);
    }

    /**
     * 声優のお気に入り解除のテスト
     *
     * @test
     * @return void
     */
    public function testUser1LoginNotExistCastUnlike()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('cast.unlike', ['cast_id' => 3333333333333333333333333333]));
        $response->assertStatus(404);
    }

    /**
     * ゲスト時の声優のお気に入り登録時リダイレクトテスト
     *
     * @test
     * @return void
     */
    public function testGuestCastLike()
    {
        $response = $this->get((route('cast.like', ['cast_id' => $this->cast->id])));
        $response->assertRedirect(route('login'));
    }

    /**
     * ゲスト時の声優のお気に入り解除時リダイレクトテスト
     *
     * @test
     * @return void
     */
    public function testGuestCastUnlike()
    {
        $response = $this->get(route('cast.unlike', ['cast_id' => $this->cast->id]));
        $response->assertRedirect(route('login'));
    }

    /**
     * 声優リストの表示のテスト
     *
     * @test
     * @return void
     */
    public function testCastListView()
    {
        $response = $this->get(route('cast_list.show'));
        $response->assertStatus(200);
    }
}
