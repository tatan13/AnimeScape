<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Anime;
use App\Models\UserReview;
use App\Models\User;
use App\Models\Cast;
use App\Models\UserLikeUser;
use App\Models\UserLikeCast;

use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private $anime1, $anime2, $user1, $user2, $user3, $user4, $cast1, $cast2, $review1, $review2,
    $user_like_user1, $user_like_user2, $user_like_user3, $user_like_user4, $user_like_user5, $user_like_cast1, $user_like_cast2;

    protected function setUp(): void
    {   
        parent::setUp();

        $this->anime1 = new Anime();
        $this->anime1->title = '霊剣山1';
        $this->anime1->title_short = '霊剣山1';
        $this->anime1->year = 2022;
        $this->anime1->coor = 1;
        $this->anime1->save();
        
        $this->anime2 = new Anime();
        $this->anime2->title = '霊剣山2';
        $this->anime2->title_short = '霊剣山2';
        $this->anime2->year = 2022;
        $this->anime2->coor = 1;
        $this->anime2->save();

        $this->user1 = User::factory()->create();
        $this->user2 = User::factory()->create();
        $this->user3 = User::factory()->create();
        $this->user4 = User::factory()->create();

        $this->cast1 = new Cast();
        $this->cast1->name = 'castname1';
        $this->cast1->save();

        $this->cast2 = new Cast();
        $this->cast2->name = 'castname2';
        $this->cast2->save();

        $this->review1 = new UserReview();
        $this->review1->anime_id = $this->anime1->id;
        $this->review1->user_id = $this->user1->id;
        $this->review1->score = 100;
        $this->review1->watch = 1;
        $this->review1->will_watch = 1;
        $this->review1->save();

        $this->review2 = new UserReview();
        $this->review2->anime_id = $this->anime2->id;
        $this->review2->user_id = $this->user1->id;
        $this->review2->score = 96;
        $this->review2->one_word_comment = 'excellent';
        $this->review2->watch = 1;
        $this->review2->will_watch = 1;
        $this->review2->save();

        $this->user_like_user1 = new UserLikeUser();
        $this->user_like_user1->user_id = $this->user1->id;
        $this->user_like_user1->liked_user_id = $this->user2->id;
        $this->user_like_user1->save();

        $this->user_like_user2 = new UserLikeUser();
        $this->user_like_user2->user_id = $this->user1->id;
        $this->user_like_user2->liked_user_id = $this->user3->id;
        $this->user_like_user2->save();

        $this->user_like_user3 = new UserLikeUser();
        $this->user_like_user3->user_id = $this->user1->id;
        $this->user_like_user3->liked_user_id = $this->user4->id;
        $this->user_like_user3->save();

        $this->user_like_user4 = new UserLikeUser();
        $this->user_like_user4->user_id = $this->user3->id;
        $this->user_like_user4->liked_user_id = $this->user1->id;
        $this->user_like_user4->save();

        $this->user_like_user5 = new UserLikeUser();
        $this->user_like_user5->user_id = $this->user4->id;
        $this->user_like_user5->liked_user_id = $this->user1->id;
        $this->user_like_user5->save();

        $this->user_like_cast1 = new UserLikeCast();
        $this->user_like_cast1->user_id = $this->user1->id;
        $this->user_like_cast1->cast_id = $this->cast1->id;
        $this->user_like_cast1->save();

        $this->user_like_cast2 = new UserLikeCast();
        $this->user_like_cast2->user_id = $this->user1->id;
        $this->user_like_cast2->cast_id = $this->cast2->id;
        $this->user_like_cast2->save();
    }

    /**
    * @test 
    */
    public function test_user_information_view()
    {
        $response = $this->get("/user_information/{$this->user1->uid}");

        $response->assertStatus(200);
        $response->assertDontSee('個人情報設定');
        $check = array('得点入力数', 2, '得点の平均', 98, '得点の中央値', 98, '一言感想入力数', 1,
                       '視聴予定数', 2, '視聴数', 2, 'お気に入りユーザー数', 3, '被お気に入りユーザー数', 2,
                       'お気に入り声優数', 2, '100', 1, '90～99', 1, '100', 100, '霊剣山1',
                       '95', 96, '霊剣山2');
        $response->assertSeeInOrder($check);
        $response->assertDontSee('Twitter :');
        $this->get(route('user.like', ['uid' => $this->user1->uid]))->assertRedirect('/login');
        $this->get(route('user.dislike', ['uid' => $this->user1->uid]))->assertRedirect('/login');
    
        $this->get('/user_information/notfound')->assertRedirect('/');
    }

    /**
    * @test 
    */
    public function test_user_information_login_mypage_view()
    {
        $this->actingAs($this->user1);
        $response = $this->get("/user_information/{$this->user1->uid}");

        $response->assertSee('個人情報設定');
    }

    /**
    * @test 
    */
    public function test_user_information_login_otherpage_view()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('user', ['uid' => $this->user2->uid]));

        $response->assertSee('お気に入りユーザーを解除する');
        $response->assertDontSee('個人情報設定');
        
        $this->get(route('user.dislike', ['uid' => $this->user2->uid]))->assertRedirect(route('user', ['uid' => $this->user2->uid]));
        $this->assertDatabaseMissing('user_like_users', [
            'user_id' => $this->user1->id,
            'liked_user_id' => $this->user2->id,
        ]);
    }

    /**
    * @test 
    */
    public function test_user_information_config_view()
    {
        $this->get(route('user.config', ['uid' => $this->user1->uid]))->assertRedirect('/login');
        $this->actingAs($this->user1);
        $this->get(route('user.config', ['uid' => $this->user2->uid]))->assertRedirect('/');
        $response = $this->get(route('user.config', ['uid' => $this->user1->uid]));

        $response->assertStatus(200);
        $response->assertSee($this->user1->uid);
    }

    /**
    * @test 
    */
    public function test_user_information_config_post()
    {
        $this->actingAs($this->user1);
        $response = $this->post(route('user.config', [
            'uid' => $this->user1->uid,
            'email' => 'example@gmail.com',
            'one_comment' => 'excellent',
            'twitter' => 't_id',
            'birth' => 1998,
            'sex' => 'f',
        ]))->assertStatus(200);

        $check = array('example@gmail.com', 'excellent', 't_id', 1998);
        $response->assertSeeInOrder($check);

        $this->get(route('user', ['uid' => $this->user1->uid]))->assertSeeInOrder(['excellent', 't_id']);
    }
}
