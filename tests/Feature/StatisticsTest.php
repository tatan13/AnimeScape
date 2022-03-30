<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Anime;
use App\Models\UserReview;
use App\Models\User;
use Tests\TestCase;

class StatisticsTest extends TestCase
{
    use RefreshDatabase;

    private $anime1, $anime2;

    protected function setUp(): void
    {   
        parent::setUp();
        $this->anime1 = new Anime();
        $this->anime1->title = '霊剣山 星屑たちの宴';
        $this->anime1->title_short = '霊剣山 星屑たちの宴';
        $this->anime1->year = 2022;
        $this->anime1->coor = 1;
        $this->anime1->median = 69;
        $this->anime1->average = 76;
        $this->anime1->count = 5;
        $this->anime1->save();

        parent::setUp();
        $this->anime2 = new Anime();
        $this->anime2->title = '霊剣山 叡智への資格';
        $this->anime2->title_short = '霊剣山 叡智への資格';
        $this->anime2->year = 2022;
        $this->anime2->coor = 1;
        $this->anime2->median = 70;
        $this->anime2->average = 70;
        $this->anime2->count = 1;
        $this->anime2->save();
    }

    /**
    * @test 
    */
    public function testAllStatisticsView()
    {
        $response = $this->get('/all_statistics/1');
        $response->assertStatus(200);
        $response->assertSee('（中央値順）');
        $check = array('霊剣山 叡智への資格', 70, '霊剣山 星屑たちの宴', 69);
        $response->assertSeeInOrder($check);
        $this->get(route('all_statistics', ['count' => 5, 'category' => 1]))->assertDontSee('霊剣山 叡智への資格')->assertSee('霊剣山 星屑たちの宴')->assertSee('（中央値順）');

        $check = array('霊剣山 星屑たちの宴', 76, '霊剣山 叡智への資格', 70);
        $this->get('/all_statistics/2')->assertSeeInOrder($check)->assertSee('（平均値順）');
        $this->get(route('all_statistics', ['count' => 5, 'category' => 2]))->assertDontSee('霊剣山 叡智への資格')->assertSee('霊剣山 星屑たちの宴')->assertSee('（中央値順）');

        $check = array('霊剣山 星屑たちの宴', 76, '霊剣山 叡智への資格', 70);
        $this->get('/all_statistics/3')->assertSeeInOrder($check)->assertSee('（データ数順）');
        $this->get(route('all_statistics', ['count' => 5, 'category' => 3]))->assertDontSee('霊剣山 叡智への資格')->assertSee('霊剣山 星屑たちの宴')->assertSee('（データ数順）');
    }

    /**
    * @test 
    */
    public function testYearStatisticsView()
    {
        $response = $this->get(route('year_statistics', ['category' => 1, 'year' => 2022]));
        $response->assertStatus(200);
        $response->assertSee('（中央値順）');
        $check = array('霊剣山 叡智への資格', 70, '霊剣山 星屑たちの宴', 69);
        $response->assertSeeInOrder($check);
        $this->get(route('year_statistics', ['count' => 5, 'category' => 1, 'year' => 2022]))->assertDontSee('霊剣山 叡智への資格')->assertSee('霊剣山 星屑たちの宴')->assertSee('（中央値順）');

        $check = array('霊剣山 星屑たちの宴', 76, '霊剣山 叡智への資格', 70);
        $this->get(route('year_statistics', ['category' => 2, 'year' => 2022]))->assertSeeInOrder($check)->assertSee('（平均値順）');
        $this->get(route('year_statistics', ['count' => 5, 'category' => 2, 'year' => 2022]))->assertDontSee('霊剣山 叡智への資格')->assertSee('霊剣山 星屑たちの宴')->assertSee('（中央値順）');

        $check = array('霊剣山 星屑たちの宴', 76, '霊剣山 叡智への資格', 70);
        $this->get(route('year_statistics', ['category' => 3, 'year' => 2022]))->assertSeeInOrder($check)->assertSee('（データ数順）');
        $this->get(route('year_statistics', ['count' => 5, 'category' => 3, 'year' => 2022]))->assertDontSee('霊剣山 叡智への資格')->assertSee('霊剣山 星屑たちの宴')->assertSee('（データ数順）');
    }

    /**
    * @test 
    */
    public function testCoorStatisticsView()
    {
        $response = $this->get(route('coor_statistics', ['category' => 1, 'year' => 2022, 'coor' => 1]));
        $response->assertStatus(200);
        $response->assertSee('（中央値順）');
        $check = array('霊剣山 叡智への資格', 70, '霊剣山 星屑たちの宴', 69);
        $response->assertSeeInOrder($check);
        $this->get(route('coor_statistics', ['count' => 5, 'category' => 1, 'year' => 2022, 'coor' => 1]))->assertDontSee('霊剣山 叡智への資格')->assertSee('霊剣山 星屑たちの宴')->assertSee('（中央値順）');

        $check = array('霊剣山 星屑たちの宴', 76, '霊剣山 叡智への資格', 70);
        $this->get(route('coor_statistics', ['category' => 2, 'year' => 2022, 'coor' => 1]))->assertSeeInOrder($check)->assertSee('（平均値順）');
        $this->get(route('coor_statistics', ['count' => 5, 'category' => 2, 'year' => 2022, 'coor' => 1]))->assertDontSee('霊剣山 叡智への資格')->assertSee('霊剣山 星屑たちの宴')->assertSee('（中央値順）');

        $check = array('霊剣山 星屑たちの宴', 76, '霊剣山 叡智への資格', 70);
        $this->get(route('coor_statistics', ['category' => 3, 'year' => 2022, 'coor' => 1]))->assertSeeInOrder($check)->assertSee('（データ数順）');
        $this->get(route('coor_statistics', ['count' => 5, 'category' => 3, 'year' => 2022, 'coor' => 1]))->assertDontSee('霊剣山 叡智への資格')->assertSee('霊剣山 星屑たちの宴')->assertSee('（データ数順）');
    }
}
