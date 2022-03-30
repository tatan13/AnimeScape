<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Cast;
use App\Models\User;
use App\Models\Anime;
use App\Models\UserLikeCast;
use App\Models\Occupation;
use Tests\TestCase;

class CastTest extends TestCase
{
    use RefreshDatabase;

    private $cast, $user, $anime1, $anime2, $occupation1, $occupation2;

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

        $this->user = User::factory()->create();

        $this->cast = new Cast();
        $this->cast->name = 'castname';
        $this->cast->save();

        $this->occupation1 = new occupation();
        $this->occupation1->anime_id = $this->anime1->id;
        $this->occupation1->cast_id = $this->cast->id;
        $this->occupation1->save();

        $this->occupation2 = new occupation();
        $this->occupation2->anime_id = $this->anime2->id;
        $this->occupation2->cast_id = $this->cast->id;
        $this->occupation2->save();
    }

    /**
    * @test 
    */  
    public function testCastGuestView()
    {
        $response = $this->get('/cast/1');

        $response->assertStatus(200);
        $response->assertSee('castname');
        $response->assertDontSee('お気に入り');
        $response->assertSee('計2本');
        $response->assertSee('霊剣山 星屑たちの宴');
        $response->assertSee('霊剣山 叡智への資格');

        $this->get('/cast/3333')->assertRedirect('/');
    }

    /**
    * @test 
    */  
    public function testCastLoginLikeView()
    {
        $this->actingAs($this->user);
        $this->get('/cast/1/like',[
            'id' => 1,
        ]);

        $this->assertDatabaseHas('user_like_casts', [
            'user_id' => 1,
            'cast_id' => 1,
        ]);

        $response = $this->get('/cast/1');

        $this->get('/cast/1/dislike',[
            'id' => 1,
        ]);

        $this->assertDatabaseMissing('user_like_casts', [
            'user_id' => 1,
            'cast_id' => 1,
        ]);


    }
}
