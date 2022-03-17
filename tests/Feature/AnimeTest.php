<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Anime;
use App\Models\UserReview;
use App\Models\User;
use Tests\TestCase;

class AnimeTest extends TestCase
{
    use RefreshDatabase;

    private $anime, $user1, $user2, $user3, $user4, $review1, $review2;

    protected function setUp(): void
    {   
        parent::setUp();
        $this->anime = new Anime();
        $this->anime->title = '霊剣山';
        $this->anime->title_short = '霊剣山';
        $this->anime->year = 2022;
        $this->anime->coor = 1;
        $this->anime->save();

        $this->user1 = User::factory()->create();
        $this->user2 = User::factory()->create();
        $this->user3 = User::factory()->create();
        $this->user4 = User::factory()->create();

        $this->review1 = new UserReview();
        $this->review1->anime_id = $this->anime->id;
        $this->review1->user_id = $this->user1->id;
        $this->review1->score = 50;
        $this->review1->watch = 1;
        $this->review1->save();

        $this->review2 = new UserReview();
        $this->review2->anime_id = $this->anime->id;
        $this->review2->user_id = $this->user2->id;
        $this->review2->one_word_comment = 'excellent';
        $this->review2->will_watch = 1;
        $this->review2->spoiler = 1;
        $this->review2->save();
    }

    /**
    * @test 
    */
    public function test_anime_guest_view()
    {
        $this->assertDatabaseHas('animes', [
            'title' => '霊剣山',
        ]);
        $response = $this->get('/anime/1');
        $response->assertSee('霊剣山');
        //ゲスト時の表示確認
        $response->assertSee('ログインしてこのアニメに得点やコメントを登録する');
        $response->assertDontSee('つけた得点');
        $response->assertStatus(200);

        $this->get('/anime/3333')->assertRedirect('/');
    }

    /**
    * @test 
    */
    public function test_anime_user1_login_view()
    {
        $this->actingAs($this->user1);
        $response = $this->get('/anime/1');
        $response->assertDontSee('ログインしてこのアニメに得点やコメントを登録する');
        $response->assertSee('つけた得点');
        $response->assertDontSee($this->user1->uid);
    }

    /**
    * @test 
    */
    public function test_anime_user2_login_view()
    {
        $this->actingAs($this->user2);
        $response = $this->get('/anime/1');
        $response->assertDontSee('つけた得点');
        $response->assertSee($this->user2->uid);
        $response->assertSee('excellent');
    }

    /**
    * @test 
    */
    public function test_anime_score_guest_view()
    {
        $response = $this->get('/anime/1/score');
        $response->assertRedirect('/login');
    }

    /**
    * @test 
    */
    public function test_anime_score_user_login_view()
    {
        $this->actingAs($this->user1);
        $response = $this->get('/anime/1/score');
        $response->assertStatus(200);
    }

    /**
    * @test 
    */
    public function test_anime_score_user1_post()
    {
        $this->actingAs($this->user1);
        $response = $this->post('/anime/1/score',[
            'score' => '',
            'one_comment' => 'exellent',
            'spoiler' => 'spoiler',
            'will_watch' => 'will_watch',
        ]);
        $this->assertDatabaseHas('user_reviews', [
            'anime_id' => 1,
            'user_id' => 1,
            'score' => null,
            'one_word_comment' => 'exellent',
            'spoiler' => 1,
            'will_watch' => 1,
            'watch' => 0,
        ]);
        $response->assertRedirect('/anime/1/score');
        $this->get('/anime/1/score')->assertSee('入力が完了しました。');
    }

    /**
    * @test 
    */    
    public function test_anime_score_user2_post()
    {
        $this->actingAs($this->user2);
        $response = $this->post('/anime/1/score',[
            'score' => 30,
            'one_comment' => null,
            'will_watch' => 'will_watch',
            'watch' => 'watch',
        ]);
        $this->assertDatabaseHas('user_reviews', [
            'anime_id' => 1,
            'user_id' => 2,
            'score' => 30,
            'one_word_comment' => null,
            'spoiler' => 0,
            'will_watch' => 0,
            'watch' => 1,
        ]);
    }

    /**
    * @test 
    */   
    public function test_anime_view()
    {
        $this->actingAs($this->user1);
        $this->post('anime/1/score',[
            'score' => 100,
        ]);

        $this->actingAs($this->user2);
        $this->post('anime/1/score',[
            'score' => 20,
        ]);

        $this->actingAs($this->user3);
        $this->post('anime/1/score',[
            'score' => 50,
        ]);

        $this->actingAs($this->user4);
        $this->post('anime/1/score',[
            'one_comment' => 'excellent',
        ]);


        $response = $this->get('/anime/1');
        $check = array('霊剣山', '2022年冬クール', 50, 56, 4, 100, 20);
        $response->assertSeeInOrder($check);
    }
}
