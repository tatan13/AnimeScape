<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Anime;
use App\Models\User;
use App\Models\Cast;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private User $user1;
    private User $user2;
    private User $user3;
    private User $user4;
    private Anime $anime1;
    private Anime $anime2;
    private Anime $anime3;
    private Anime $anime4;
    private Anime $anime5;
    private Anime $anime6;
    private Cast $cast1;
    private Cast $cast2;
    private Cast $cast3;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user1 = User::factory()->create([
            'onewordcomment' => 'exellent',
            'twitter' => 'twitterId',
            'birth' => 1998,
            'sex' => 1,
        ]);
        $this->user2 = User::factory()->create([
            'email' => null,
            'onewordcomment' => null,
            'twitter' => null,
            'birth' => null,
            'sex' => null,
        ]);
        $this->user3 = User::factory()->create();
        $this->user4 = User::factory()->create();

        $this->anime1 = Anime::factory()->create();
        $this->anime2 = Anime::factory()->create();
        $this->anime3 = Anime::factory()->create();
        $this->anime4 = Anime::factory()->create([
            'year' => 2022,
            'coor' => 2,
        ]);
        $this->anime5 = Anime::factory()->create([
            'year' => 2021,
            'coor' => 1,
        ]);
        $this->anime6 = Anime::factory()->create();

        $this->cast1 = Cast::factory()->create();
        $this->cast2 = Cast::factory()->create();
        $this->cast3 = Cast::factory()->create();

        $this->anime1->reviewUsers()->attach($this->user1->id, [
            'score' => 100,
            'one_word_comment' => 'excellent',
            'watch' => true
        ]);
        $this->anime2->reviewUsers()->attach($this->user1->id, [
            'score' => 99,
            'one_word_comment' => 'not sad',
            'watch' => true
        ]);
        $this->anime3->reviewUsers()->attach($this->user1->id, [
            'score' => 95,
            'watch' => true
        ]);
        $this->anime4->reviewUsers()->attach($this->user1->id, [
            'score' => 5,
            'watch' => true
        ]);
        $this->anime5->reviewUsers()->attach($this->user1->id, [
            'score' => 0,
            'will_watch' => true
        ]);
        $this->anime6->reviewUsers()->attach($this->user1->id, [
            'will_watch' => true
        ]);

        $this->anime6->reviewUsers()->attach($this->user2->id, [
            'score' => 50,
            'one_word_comment' => 'false',
            'will_watch' => true,
            'watch' => true
        ]);

        $this->user1->likeCasts()->attach($this->cast1->id);
        $this->user1->likeCasts()->attach($this->cast2->id);
        $this->user2->likeCasts()->attach($this->cast3->id);

        $this->user1->userLikeUsers()->attach($this->user2->id);
        $this->user1->userLikeUsers()->attach($this->user3->id);
        $this->user1->userLikeUsers()->attach($this->user4->id);

        $this->user3->userLikeUsers()->attach($this->user1->id);
        $this->user4->userLikeUsers()->attach($this->user1->id);
    }

    /**
     * ゲスト時のプロフィール入力済みユーザーのプロフィールの表示のテスト
     *
     * @test
     * @return void
     */
    public function testGuestUser1ProfileView()
    {
        $response = $this->get("/user_information/{$this->user1->uid}");
        $response->assertDontSee('個人情報設定');
        $response->assertSeeInOrder([
            $this->user1->uid,
            $this->user1->onewordcomment,
            $this->user1->twitter,
        ]);
    }

    /**
     * ログイン時のプロフィール未入力の他ユーザーのプロフィールの表示のテスト
     *
     * @test
     * @return void
     */
    public function testUser1LoginUser2ProfileView()
    {
        $this->actingAs($this->user1);
        $response = $this->get("/user_information/{$this->user2->uid}");
        $response->assertDontSee('個人情報設定');
        $response->assertDontSee('class="one_comment"');
        $response->assertDontSee('Twitter : ');
    }

    /**
     * ログイン時のお気に入り未登録他ユーザーのお気に入り登録テスト
     *
     * @test
     * @return void
     */
    public function testUser2LoginUser1Like()
    {
        $this->actingAs($this->user2);
        $response = $this->get("/user_information/{$this->user1->uid}/like");
        $this->assertDatabaseHas('user_like_users', [
            'id' => 6,
            'user_id' => $this->user2->id,
            'liked_user_id' => $this->user1->id,
        ]);
    }

    /**
     * ログイン時のお気に入り登録済他ユーザーのお気に入り解除テスト
     *
     * @test
     * @return void
     */
    public function testUser1LoginUser2Unlike()
    {
        $this->actingAs($this->user1);
        $response = $this->get("/user_information/{$this->user2->uid}/unlike");
        $this->assertDatabaseMissing('user_like_users', [
            'id' => 1,
            'user_id' => $this->user1->id,
            'liked_user_id' => $this->user2->id,
        ]);
    }

    /**
     * ログイン時のお気に入り登録済他ユーザーのお気に入り登録テスト
     *
     * @test
     * @return void
     */
    public function testUser1LoginUser2Like()
    {
        $this->actingAs($this->user1);
        $response = $this->get("/user_information/{$this->user2->uid}/like");
        $this->assertDatabaseMissing('user_like_users', [
            'id' => 6,
            'user_id' => $this->user1->id,
            'liked_user_id' => $this->user2->id,
        ]);
    }

    /**
     * ゲスト時のユーザーのお気に入り登録時のリダイレクトテスト
     *
     * @test
     * @return void
     */
    public function testGuestUser1Like()
    {
        $response = $this->get("/user_information/{$this->user1->uid}/like");
        $response->assertRedirect('/login');
    }

    /**
     * ゲスト時のユーザーのお気に入り解除時のリダイレクトテスト
     *
     * @test
     * @return void
     */
    public function testGuestUser1Unlike()
    {
        $response = $this->get("/user_information/{$this->user1->uid}/unlike");
        $response->assertRedirect('/login');
    }

    /**
     * ユーザーのすべてのアニメの統計情報の表示のテスト
     *
     * @test
     * @return void
     */
    public function testUser1AllStatisticView()
    {
        $response = $this->get("/user_information/{$this->user1->uid}");
        $response->assertSeeInOrder([
            '統計情報(すべて)',
            '得点入力数',
            5,
            '得点の平均',
            59,
            '得点の中央値',
            95,
            '一言感想入力数',
            2,
            '視聴予定数',
            2,
            '視聴数',
            4,
            'お気に入りユーザー数',
            3,
            'お気に入り声優数',
            2,
            '100',
            1,
            '90～99',
            2,
            '0～9',
            2,
            '100',
            100,
            $this->anime1->title,
            '95',
            99,
            $this->anime2->title,
            95,
            $this->anime3->title,
            '0',
            5,
            $this->anime4->title,
            0,
            $this->anime5->title,
        ]);
        $response->assertDontSee($this->anime6->title);
    }

    /**
     * ユーザーの年別のアニメの統計情報の表示のテスト
     *
     * @test
     * @return void
     */
    public function testUser1YearStatisticView()
    {
        $response = $this->get(route('user.show', [
                'uid' => $this->user1->uid,
                'year' => 2022,
            ]));
        $response->assertSeeInOrder([
                '統計情報(2022年)',
                '得点入力数',
                4,
                '得点の平均',
                74,
                '得点の中央値',
                97,
                '一言感想入力数',
                2,
                '視聴予定数',
                1,
                '視聴数',
                4,
                '100',
                1,
                '90～99',
                2,
                '0～9',
                1,
                '100',
                100,
                $this->anime1->title,
                '95',
                99,
                $this->anime2->title,
                95,
                $this->anime3->title,
                '0',
                5,
                $this->anime4->title,
            ]);
        $response->assertDontSee($this->anime5->title);
    }

    /**
     * ユーザーのクール別のアニメの統計情報の表示のテスト
     *
     * @test
     * @return void
     */
    public function testUser1CoorStatisticView()
    {
        $response = $this->get(route('user.show', [
            'uid' => $this->user1->uid,
            'year' => 2022,
            'coor' => 1,
        ]));
        $response->assertSeeInOrder([
            '統計情報(2022年冬クール)',
            '得点入力数',
            3,
            '得点の平均',
            98,
            '得点の中央値',
            99,
            '一言感想入力数',
            2,
            '視聴予定数',
            0,
            '視聴数',
            3,
            '100',
            1,
            '90～99',
            2,
            '0～9',
            0,
            '100',
            100,
            $this->anime1->title,
            '95',
            99,
            $this->anime2->title,
            95,
            $this->anime3->title,
        ]);
        $response->assertDontSee($this->anime4->title);
    }

    /**
     * ユーザーのアニメの統計情報リクエスト時にクールのみ入力した場合のテスト
     *
     * @test
     * @return void
     */
    public function testUser1CoorNullYearStatisticView()
    {
        $response = $this->get(route('user.show', [
            'uid' => $this->user1->uid,
            'coor' => 1,
        ]));
        $response->assertStatus(404);
    }

    /**
     * ユーザーのアニメの統計情報が一つもない場合のテスト
     *
     * @test
     * @return void
     */
    public function testUser1NullStatisticView()
    {
        $response = $this->get(route('user.show', [
            'uid' => $this->user1->uid,
            'year' => 0,
            'coor' => 0,
        ]));
        $response->assertStatus(200);
    }

    /**
     * 存在しないユーザーページをリクエストした場合のテスト
     *
     * @test
     * @return void
     */
    public function testNotExistUser1View()
    {
        $response = $this->get(route('user.show', [
            'uid' => 'NotExistUser',
        ]));
        $response->assertStatus(404);
    }

    /**
     * ユーザーの視聴予定表の表示のテスト
     *
     * @test
     * @return void
     */
    public function testUser1WillWatchAnimeListView()
    {
        $response = $this->get("/user_information/{$this->user1->uid}/will_watch_anime_list");
        $response->assertSeeInOrder([
            $this->anime6->title,
            $this->anime5->title,
        ]);
    }

    /**
     * ユーザーのお気に入りユーザーリストの表示のテスト
     *
     * @test
     * @return void
     */
    public function testUser1LikeUserListView()
    {
        $response = $this->get("/user_information/{$this->user1->uid}/like_user_list");
        $response->assertSeeInOrder([
            $this->user2->uid,
            $this->user3->uid,
            $this->user4->uid,
        ]);
    }

    /**
     * ユーザーの被お気に入りユーザーリストの表示のテスト
     *
     * @test
     * @return void
     */
    public function testUser1LikedUserListView()
    {
        $response = $this->get("/user_information/{$this->user1->uid}/liked_user_list");
        $response->assertSeeInOrder([
            $this->user3->uid,
            $this->user4->uid,
        ]);
    }

    /**
     * ユーザーのお気に入り声優リストの表示のテスト
     *
     * @test
     * @return void
     */
    public function testUser1LikeCastListView()
    {
        $response = $this->get("/user_information/{$this->user1->uid}/like_cast_list");
        $response->assertSeeInOrder([
            $this->cast1->name,
            $this->cast2->name,
        ]);
    }

    /**
     * ゲスト時のユーザー情報変更ページリクエスト時のリダイレクトテスト
     *
     * @test
     * @return void
     */
    public function testGuestUserConfigView()
    {
        $response = $this->get("/user_config");
        $response->assertRedirect("/login");
    }

    /**
     * ログイン時のユーザー情報変更ページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testLoginUserConfigView()
    {
        $this->actingAs($this->user1);
        $response = $this->get("/user_config");
        $response->assertSeeInOrder([
            $this->user1->uid,
            $this->user1->email,
            $this->user1->onewordcomment,
            $this->user1->twitter,
            $this->user1->birth,
        ]);
    }

    /**
     * プロフィール入力済みユーザー情報変更入力のテスト
     *
     * @test
     * @return void
     */
    public function testLoginUserConfigNullPost()
    {
        $this->actingAs($this->user1);
        $response = $this->post("/user_config", [
            'email' => null,
            'onewordcomment' => null,
            'twitter' => null,
            'birth' => null,
            'sex' => null,
        ]);
        $response->assertRedirect("/user_config");
        $this->assertDatabaseHas('users', [
            'uid' => $this->user1->uid,
            'email' => null,
            'onewordcomment' => null,
            'twitter' => null,
            'birth' => null,
            'sex' => null,
        ]);
    }

    /**
     * プロフィール未入力ユーザー情報変更入力のテスト
     *
     * @test
     * @return void
     */
    public function testLoginUserConfigPost()
    {
        $this->actingAs($this->user2);
        $response = $this->post("/user_config", [
            'email' => 'example@gmail.com',
            'onewordcomment' => 'excellent',
            'twitter' => 't_id',
            'birth' => 1998,
            'sex' => false,
        ]);
        $response->assertRedirect("/user_config");
        $this->assertDatabaseHas('users', [
            'uid' => $this->user2->uid,
            'email' => 'example@gmail.com',
            'onewordcomment' => 'excellent',
            'twitter' => 't_id',
            'birth' => 1998,
            'sex' => false,
        ]);
    }
}
