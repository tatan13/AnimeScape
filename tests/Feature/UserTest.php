<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Anime;
use App\Models\User;
use App\Models\Cast;
use App\Models\Creater;
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
    private Creater $creater1;
    private Creater $creater2;
    private Creater $creater3;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user1 = User::factory()->create([
            'one_comment' => 'exellent',
            'twitter' => 'twitterId',
            'birth' => 1998,
            'sex' => 1,
        ]);
        $this->user2 = User::factory()->create([
            'email' => null,
            'one_comment' => null,
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

        $this->creater1 = Creater::factory()->create();
        $this->creater2 = Creater::factory()->create();
        $this->creater3 = Creater::factory()->create();

        $this->anime1->reviewUsers()->attach($this->user1->id, [
            'score' => 100,
            'one_word_comment' => 'excellent',
            'long_word_comment' => 'long_word_comment_exellent',
            'watch' => true,
            'now_watch' => true,
            'give_up' => true,
            'before_score' => 100,
            'before_comment' => 'excellent',
        ]);
        $this->anime2->reviewUsers()->attach($this->user1->id, [
            'score' => 99,
            'one_word_comment' => 'not sad',
            'watch' => true,
            'now_watch' => true,
            'give_up' => true,
            'before_score' => 99,
            'before_comment' => 'not sad',
        ]);
        $this->anime3->reviewUsers()->attach($this->user1->id, [
            'score' => 95,
            'watch' => true,
            'before_score' => 95,
        ]);
        $this->anime4->reviewUsers()->attach($this->user1->id, [
            'score' => 5,
            'watch' => true,
            'before_score' => 5,
        ]);
        $this->anime5->reviewUsers()->attach($this->user1->id, [
            'score' => 0,
            'will_watch' => true,
            'before_score' => 0,
        ]);
        $this->anime6->reviewUsers()->attach($this->user1->id, [
            'will_watch' => true,
        ]);

        $this->anime6->reviewUsers()->attach($this->user2->id, [
            'score' => 50,
            'one_word_comment' => 'false',
            'will_watch' => true,
            'watch' => true,
            'before_score' => 50,
        ]);

        $this->user1->likeCasts()->attach($this->cast1->id);
        $this->user1->likeCasts()->attach($this->cast2->id);
        $this->user2->likeCasts()->attach($this->cast3->id);

        $this->user1->likeCreaters()->attach($this->creater1->id);
        $this->user1->likeCreaters()->attach($this->creater2->id);
        $this->user2->likeCreaters()->attach($this->creater3->id);

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
        $response = $this->get(route('user.show', ['user_id' => $this->user1->id]));
        $response->assertStatus(200);
        $response->assertDontSee('個人情報設定');
        $response->assertSeeInOrder([
            $this->user1->name,
            $this->user1->one_comment,
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
        $response = $this->get(route('user.show', ['user_id' => $this->user2->id]));
        $response->assertStatus(200);
        $response->assertDontSee('個人情報設定');
        $response->assertDontSee('class="one_comment"');
        $response->assertDontSee('Twitter : ');
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
            'user_id' => 333333333333333333333,
        ]));
        $response->assertStatus(404);
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
        $response = $this->get((route('user.like', ['user_id' => $this->user1->id])));
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
        $response = $this->get(route('user.unlike', ['user_id' => $this->user2->id]));
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
        $response = $this->get(route('user.like', ['user_id' => $this->user2->id]));
        $this->assertDatabaseMissing('user_like_users', [
            'id' => 6,
            'user_id' => $this->user1->id,
            'liked_user_id' => $this->user2->id,
        ]);
    }

    /**
     * ユーザーのお気に入り解除の異常値テスト
     *
     * @test
     * @return void
     */
    public function testUser1NotExistUserUnlike()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('user.unlike', ['user_id' => 333333333333333333333333333]));
        $response->assertStatus(404);
    }

    /**
     * ユーザーのお気に入り登録の異常値テスト
     *
     * @test
     * @return void
     */
    public function testUser1LoginNotExistUserLike()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('user.like', ['user_id' => 333333333333333333333]));
        $response->assertStatus(404);
    }

    /**
     * ゲスト時のユーザーのお気に入り登録時のリダイレクトテスト
     *
     * @test
     * @return void
     */
    public function testGuestUser1Like()
    {
        $response = $this->get(route('user.like', ['user_id' => $this->user1->id]));
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
        $response = $this->get(route('user.unlike', ['user_id' => $this->user1->id]));
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
        $response = $this->get(route('user.show', ['user_id' => $this->user1->id]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            '統計情報(すべて)',
            '得点入力数',
            5,
            '得点の平均',
            59,
            '得点の中央値',
            95,
            '感想入力数',
            2,
            '視聴予定数',
            2,
            '視聴数',
            4,
            '視聴中数',
            2,
            '視聴リタイア数',
            2,
            '視聴完了前得点入力数',
            5,
            '視聴完了前一言感想入力数',
            2,
            'お気に入りユーザー数',
            3,
            'お気に入り声優数',
            2,
            'お気に入りクリエイター数',
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
                'user_id' => $this->user1->id,
                'year' => 2022,
            ]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
                '統計情報(2022年)',
                '得点入力数',
                4,
                '得点の平均',
                74,
                '得点の中央値',
                97,
                '感想入力数',
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
            'user_id' => $this->user1->id,
            'year' => 2022,
            'coor' => 1,
        ]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            '統計情報(2022年冬クール)',
            '得点入力数',
            3,
            '得点の平均',
            98,
            '得点の中央値',
            99,
            '感想入力数',
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
            'user_id' => $this->user1->id,
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
            'user_id' => $this->user1->id,
            'year' => 0,
            'coor' => 0,
        ]));
        $response->assertStatus(200);
    }

    /**
     * ユーザーの感想を付けたアニメリストの表示のテスト
     *
     * @test
     * @return void
     */
    public function testUser1CommentAnimeListView()
    {
        $response = $this->get(route('user_comment_anime_list.show', ['user_id' => $this->user1->id]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            100,
            $this->anime1->title,
            'excellent',
            '長文感想',
            99,
            $this->anime2->title,
            'not sad',
        ]);
    }

    /**
     * ユーザーの感想を付けたアニメリストの表示の異常値テスト
     *
     * @test
     * @return void
     */
    public function testNotExistUserCommentAnimeListView()
    {
        $response = $this->get(route('user_comment_anime_list.show', ['user_id' => 33333333333333333]));
        $response->assertStatus(404);
    }

    /**
     * ユーザーの得点を付けたアニメリストの表示のテスト
     *
     * @test
     * @return void
     */
    public function testUser1ScoreAnimeListView()
    {
        $response = $this->get(route('user_score_anime_list.show', ['user_id' => $this->user1->id]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->anime1->title,
            $this->anime2->title,
        ]);
    }

    /**
     * ユーザーの得点を付けたアニメリストの表示の異常値テスト
     *
     * @test
     * @return void
     */
    public function testNotExistUserScoreAnimeListView()
    {
        $response = $this->get(route('user_score_anime_list.show', ['user_id' => 33333333333333333]));
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
        $response = $this->get(route('user_will_watch_anime_list.show', ['user_id' => $this->user1->id]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->anime6->title,
            $this->anime5->title,
        ]);
    }

    /**
     * ユーザーの視聴予定表の表示の異常値テスト
     *
     * @test
     * @return void
     */
    public function testNotExistUserWillWatchAnimeListView()
    {
        $response = $this->get(route('user_will_watch_anime_list.show', ['user_id' => 3333333333333333333333333]));
        $response->assertStatus(404);
    }

    /**
     * ユーザーの視聴アニメリストの表示のテスト
     *
     * @test
     * @return void
     */
    public function testUser1WatchAnimeListView()
    {
        $response = $this->get(route('user_watch_anime_list.show', ['user_id' => $this->user1->id]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->anime1->title,
            $this->anime2->title,
            $this->anime3->title,
            $this->anime4->title,
        ]);
    }

    /**
     * ユーザーの視聴アニメリストの表示の異常値テスト
     *
     * @test
     * @return void
     */
    public function testNotExistUserWatchAnimeListView()
    {
        $response = $this->get(route('user_watch_anime_list.show', ['user_id' => 3333333333333333333333333]));
        $response->assertStatus(404);
    }

    /**
     * ユーザーの視聴中アニメリストの表示のテスト
     *
     * @test
     * @return void
     */
    public function testUser1NowWatchAnimeListView()
    {
        $response = $this->get(route('user_now_watch_anime_list.show', ['user_id' => $this->user1->id]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->anime1->title,
            $this->anime2->title,
        ]);
    }

    /**
     * ユーザーの視聴中アニメリストの表示の異常値テスト
     *
     * @test
     * @return void
     */
    public function testNotExistUserNowWatchAnimeListView()
    {
        $response = $this->get(route('user_now_watch_anime_list.show', ['user_id' => 3333333333333333333333333]));
        $response->assertStatus(404);
    }

    /**
     * ユーザーの視聴放棄アニメリストの表示のテスト
     *
     * @test
     * @return void
     */
    public function testUser1GiveUpAnimeListView()
    {
        $response = $this->get(route('user_give_up_anime_list.show', ['user_id' => $this->user1->id]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->anime1->title,
            $this->anime2->title,
        ]);
    }

    /**
     * ユーザーの視聴放棄アニメリストの表示の異常値テスト
     *
     * @test
     * @return void
     */
    public function testNotExistUserGiveUpAnimeListView()
    {
        $response = $this->get(route('user_give_up_anime_list.show', ['user_id' => 3333333333333333333333333]));
        $response->assertStatus(404);
    }

    /**
     * ユーザーの視聴完了前得点を付けたアニメリストの表示のテスト
     *
     * @test
     * @return void
     */
    public function testUser1BeforeScoreAnimeListView()
    {
        $response = $this->get(route('user_before_score_anime_list.show', ['user_id' => $this->user1->id]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->anime1->title,
            $this->anime2->title,
        ]);
    }

    /**
     * ユーザーの視聴完了前得点を付けたアニメリストの表示の異常値テスト
     *
     * @test
     * @return void
     */
    public function testNotExistUserBeforeScoreAnimeListView()
    {
        $response = $this->get(route('user_before_score_anime_list.show', ['user_id' => 33333333333333333]));
        $response->assertStatus(404);
    }

    /**
     * ユーザーの視聴完了前感想を付けたアニメリストの表示のテスト
     *
     * @test
     * @return void
     */
    public function testUser1BeforeCommentAnimeListView()
    {
        $response = $this->get(route('user_before_comment_anime_list.show', ['user_id' => $this->user1->id]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            100,
            $this->anime1->title,
            'excellent',
            99,
            $this->anime2->title,
            'not sad',
        ]);
    }

    /**
     * ユーザーの視聴完了前感想を付けたアニメリストの表示の異常値テスト
     *
     * @test
     * @return void
     */
    public function testNotExistUserBeforeCommentAnimeListView()
    {
        $response = $this->get(route('user_before_comment_anime_list.show', ['user_id' => 33333333333333333]));
        $response->assertStatus(404);
    }

    /**
     * ユーザーのお気に入りユーザーリストの表示のテスト
     *
     * @test
     * @return void
     */
    public function testUser1LikeUserListView()
    {
        $response = $this->get(route('user_like_user_list.show', ['user_id' => $this->user1->id]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->user2->name,
            $this->user3->name,
            $this->user4->name,
        ]);
    }

    /**
     * ユーザーのお気に入りユーザーリストの表示の異常値テスト
     *
     * @test
     * @return void
     */
    public function testNotExistUserLikeUserListView()
    {
        $response = $this->get(route('user_like_user_list.show', ['user_id' => 3333333333333333]));
        $response->assertStatus(404);
    }

    /**
     * ユーザーの被お気に入りユーザーリストの表示のテスト
     *
     * @test
     * @return void
     */
    public function testUser1LikedUserListView()
    {
        $response = $this->get(route('user_liked_user_list.show', ['user_id' => $this->user1->id]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->user3->name,
            $this->user4->name,
        ]);
    }

    /**
     * ユーザーの被お気に入りユーザーリストの表示の異常値テスト
     *
     * @test
     * @return void
     */
    public function testNotExistUserLikedUserListView()
    {
        $response = $this->get(route('user_liked_user_list.show', ['user_id' => 3333333333333333333333]));
        $response->assertStatus(404);
    }

    /**
     * ユーザーのお気に入り声優リストの表示のテスト
     *
     * @test
     * @return void
     */
    public function testUser1LikeCastListView()
    {
        $response = $this->get(route('user_like_cast_list.show', ['user_id' => $this->user1->id]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->cast1->name,
            $this->cast2->name,
        ]);
    }

    /**
     * ユーザーのお気に入り声優リストの表示の異常値テスト
     *
     * @test
     * @return void
     */
    public function testNotExistUserLikeCastListView()
    {
        $response = $this->get(route('user_like_cast_list.show', ['user_id' => 33333333333333333333]));
        $response->assertStatus(404);
    }

    /**
     * ユーザーのお気に入りクリエイターリストの表示のテスト
     *
     * @test
     * @return void
     */
    public function testUser1LikeCreaterListView()
    {
        $response = $this->get(route('user_like_creater_list.show', ['user_id' => $this->user1->id]));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->creater1->name,
            $this->creater2->name,
        ]);
    }

    /**
     * ユーザーのお気に入りクリエイターリストの表示の異常値テスト
     *
     * @test
     * @return void
     */
    public function testNotExistUserLikeCreaterListView()
    {
        $response = $this->get(route('user_like_creater_list.show', ['user_id' => 33333333333333333333]));
        $response->assertStatus(404);
    }

    /**
     * ゲスト時のユーザー情報変更ページリクエスト時のリダイレクトテスト
     *
     * @test
     * @return void
     */
    public function testGuestUserConfigView()
    {
        $response = $this->get(route('user_config.show'));
        $response->assertRedirect(route('login'));
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
        $response = $this->get(route('user_config.show'));
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            $this->user1->name,
            $this->user1->email,
            $this->user1->one_comment,
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
        $response = $this->post(route('user_config.post'), [
            'name' => $this->user1->name,
            'email' => null,
            'one_comment' => null,
            'twitter' => null,
            'birth' => null,
            'sex' => null,
        ]);
        $response->assertRedirect(route('user_config.show'));
        $this->assertDatabaseHas('users', [
            'name' => $this->user1->name,
            'email' => null,
            'one_comment' => null,
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
        $response = $this->post(route('user_config.post'), [
            'name' => 'modify_name',
            'email' => 'example@gmail.com',
            'one_comment' => 'excellent',
            'twitter' => 't_id',
            'birth' => 1998,
            'sex' => 0,
        ]);
        $response->assertRedirect(route('user_config.show'));
        $this->assertDatabaseHas('users', [
            'name' => 'modify_name',
            'email' => 'example@gmail.com',
            'one_comment' => 'excellent',
            'twitter' => 't_id',
            'birth' => 1998,
            'sex' => 0,
        ]);
    }

    /**
     * ユーザーのアニメコメントを表示
     *
     * @test
     * @return void
     */
    public function testUserAnimeCommentView()
    {
        $response = $this->get(route('user_anime_comment.show', ['user_review_id' => 1]));
        $response->assertStatus(200);
        $user_review = $this->user1->userReviews()->where('anime_id', $this->anime1->id)->first();
        $response->assertSeeInOrder([
            $this->user1->name,
            $this->anime1->title,
            $user_review->score,
            $user_review->one_word_comment,
            $user_review->long_word_comment,
        ]);
    }

    /**
     * 存在しないユーザーのアニメコメントを表示
     *
     * @test
     * @return void
     */
    public function testNotExistUserAnimeCommentView()
    {
        $response = $this->get(route('user_anime_comment.show', ['user_review_id' => 3333333333333]));
        $response->assertStatus(404);
    }

    /**
     * 新着一言感想一覧の表示のテスト
     *
     * @test
     * @return void
     */
    public function testNewCommentListView()
    {
        $response = $this->get(route('new_comment_list.show'));
        $response->assertStatus(200);
    }

    /**
     * 新着視聴完了前一言感想一覧の表示のテスト
     *
     * @test
     * @return void
     */
    public function testNewBeforeCommentListView()
    {
        $response = $this->get(route('new_before_comment_list.show'));
        $response->assertStatus(200);
    }
}
