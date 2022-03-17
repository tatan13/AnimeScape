<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Anime;
use App\Models\UserReview;
use App\Models\User;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    private $anime1, $anime2, $user1, $user2, $user3;

    protected function setUp(): void
    {   
        parent::setUp();
        $this->anime1 = new Anime();
        $this->anime1->title = '霊剣山 星屑たちの宴';
        $this->anime1->title_short = '霊剣山 星屑たちの宴';
        $this->anime1->year = 2022;
        $this->anime1->coor = 1;
        $this->anime1->save();

        $this->anime2 = new Anime();
        $this->anime2->title = '霊剣山 叡智への資格';
        $this->anime2->title_short = '霊剣山 叡智への資格';
        $this->anime2->year = 2022;
        $this->anime2->coor = 1;
        $this->anime2->save();

        $this->user1 = User::factory()->create();
        $this->user2 = User::factory()->create();
        $this->user3 = User::factory()->create();
    }

    /**
    * @test 
    */
    public function test_index_view()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        //ゲスト時の表示確認
        $response->assertSee('新規ID作成');
    }

    /**
    * @test 
    */
    public function test_login_view()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('/');

        $response->assertSee('ログイン中です。');
    }

    /**
    * @test 
    */
    public function test_index_anime_view()
    {
        $this->actingAs($this->user1);
        $this->post('anime/1/score',[
            'score' => 100,
        ]);
        $this->post('anime/2/score',[
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


        $response = $this->get('/');

        $check = array($this->anime2->title, 100, 1, $this->anime1->title, 50, 3);

        $response->assertSeeInOrder($check);
    }
}
