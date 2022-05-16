<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Cast;
use App\Models\User;
use App\Models\Anime;
use App\Models\UserLikeCast;
use Tests\TestCase;

class CastTest extends TestCase
{
    use RefreshDatabase;

    private Cast $cast;
    private User $user1;
    private User $user2;
    private Anime $anime1;
    private Anime $anime2;

    protected function setUp(): void
    {
        parent::setUp();
        $this->anime1 = Anime::factory()->create([
            'title' => '霊剣山 星屑たちの宴',
            'company' => 'company1',
            'median' => 78,
            'count' => 332,
        ]);
        $this->anime2 = Anime::factory()->create([
            'title' => '霊剣山 叡智への資格',
            'company' => 'company2',
            'median' => 80,
            'count' => 232,
        ]);

        $this->cast = Cast::factory()->create();

        $this->user1 = User::factory()->create();
        $this->user2 = User::factory()->create();

        $this->user2->likeCasts()->attach($this->cast->id);

        $this->anime1->actCasts()->attach($this->cast->id);
        $this->anime2->actCasts()->attach($this->cast->id);
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
            '霊剣山 星屑たちの宴',
            'company1',
            '2022年冬クール',
            78,
            332,
            '霊剣山 叡智への資格',
            'company2',
            '2022年冬クール',
            80,
            232,
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
        $response->assertDontSee('お気に入り');
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
            'id' => 2,
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
}
