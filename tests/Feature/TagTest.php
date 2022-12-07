<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Anime;
use App\Models\Tag;
use App\Models\Company;
use App\Models\User;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    private Anime $anime;
    private Anime $anime1;
    private Company $company;
    private Company $company1;
    private Tag $tag;
    private User $user1;
    private User $user2;
    private User $user3;
    private User $user4;

    protected function setUp(): void
    {
        parent::setUp();
        $this->anime = Anime::factory()->create();
        $this->anime1 = Anime::factory()->create();

        $this->tag = Tag::factory()->create();

        $this->company = Company::factory()->create();
        $this->company1 = Company::factory()->create();
        $this->anime->companies()->attach($this->company->id);
        $this->anime->companies()->attach($this->company1->id);

        $this->user1 = User::factory()->create();
        $this->user2 = User::factory()->create();
        $this->user3 = User::factory()->create();
        $this->user4 = User::factory()->create();

        $this->anime->tags()->attach($this->tag->id, [
            'user_id' => $this->user1->id,
            'score' => 0,
        ]);
        $this->anime->tags()->attach($this->tag->id, [
            'user_id' => $this->user2->id,
            'score' => 30,
        ]);
        $this->anime->tags()->attach($this->tag->id, [
            'user_id' => $this->user3->id,
            'score' => 60,
        ]);
        $this->anime->tags()->attach($this->tag->id, [
            'user_id' => $this->user4->id,
            'score' => 100,
        ]);
        $this->anime1->tags()->attach($this->tag->id, [
            'user_id' => $this->user1->id,
            'score' => 100,
        ]);
    }

    /**
     * タグページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testTagView()
    {
        $response = $this->get(route('tag.show', ['tag_id' => $this->tag->id]));
        $response->assertStatus(200);
    }

    /**
     * タグページのタグ情報の表示のテスト
     *
     * @test
     * @return void
     */
    public function testTagInformationView()
    {
        $response = $this->get(route('tag.show', ['tag_id' => $this->tag->id]));
        $response->assertSeeInOrder([
            $this->tag->name,
            $this->anime->title,
            $this->company->name,
            $this->company1->name,
            '4件',
            '中央値45点',
            $this->anime1->title
        ]);
    }

    /**
     * 存在しないタグページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testNotExistTagView()
    {
        $response = $this->get(route('tag.show', ['tag_id' => 33333]));
        $response->assertStatus(404);
    }

    /**
     * タグリストの表示のテスト
     *
     * @test
     * @return void
     */
    public function testTagListView()
    {
        $response = $this->get(route('tag_list.show'));
        $response->assertStatus(200);
    }
}
