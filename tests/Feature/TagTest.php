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
    private Anime $anime2;
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
        $this->anime2 = Anime::factory()->create();

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
            'comment' => 'this is tag comment',
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
     * ゲスト時のタグレビューページのリダイレクトのテスト
     *
     * @test
     * @return void
     */
    public function testGuestTagReviewView()
    {
        $response = $this->get(route('tag_review.show', ['tag_id' => $this->tag->id]));
        $response->assertRedirect(route('login'));
    }

    /**
     * ログイン時のタグレビューページの表示のテスト
     *
     * @test
     * @return void
     */
    public function testUser1LoginTagReviewView()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('tag_review.show', ['tag_id' => $this->tag->id]));
        $response->assertStatus(200);
    }

    /**
     * ログイン時のタグレビューページの表示の異常値テスト
     *
     * @test
     * @return void
     */
    public function testUser1LoginNotExistTagReviewView()
    {
        $this->actingAs($this->user1);
        $response = $this->get(route('tag_review.show', ['tag_id' => 333333333333333333333]));
        $response->assertStatus(404);
    }

    /**
     * ユーザーのタグレビュー変更なしのテスト
     *
     * @test
     * @return void
     */
    public function testUser1TagReviewNoChangePost()
    {
        $this->actingAs($this->user1);
        $response = $this->post(route('tag_review.post', [
            'tag_id' => $this->tag->id,
            'tag_review_id[1]' => 1,
            'modify_type[1]' => 'no_change',
            'anime_id[1]' => $this->anime->id,
            'score[1]' => 35,
            'comment[1]' => 'no change'
        ]));
        $this->assertDatabaseHas('tag_reviews', [
            'anime_id' => $this->anime->id,
            'user_id' => $this->user1->id,
            'tag_id' => $this->tag->id,
            'score' => 0,
            'comment' => 'this is tag comment',
        ]);
        $response->assertRedirect(route('tag.show', ['tag_id' => $this->tag->id]));
        $this->get(route('tag.show', ['tag_id' => $this->tag->id]))->assertSee('入力が完了しました。');
    }

    /**
     * ユーザーのタグレビュー削除のテスト
     *
     * @test
     * @return void
     */
    public function testUser1TagReviewDeletePost()
    {
        $this->actingAs($this->user1);
        $response = $this->post(route('tag_review.post', [
            'tag_id' => $this->tag->id,
            'tag_review_id[1]' => 1,
            'modify_type[1]' => 'delete',
            'anime_id[1]' => $this->anime->id,
            'score[1]' => 35,
            'comment[1]' => 'delete'
        ]));
        $this->assertDatabaseMissing('tag_reviews', [
            'anime_id' => $this->anime->id,
            'user_id' => $this->user1->id,
            'tag_id' => $this->tag->id,
            'score' => 0,
            'comment' => 'this is tag comment',
        ]);
        $response->assertRedirect(route('tag.show', ['tag_id' => $this->tag->id]));
        $this->get(route('tag.show', ['tag_id' => $this->tag->id]))->assertSee('入力が完了しました。');
    }

    /**
     * ユーザーのタグレビュー変更のテスト
     *
     * @test
     * @return void
     */
    public function testUser1TagReviewChangePost()
    {
        $this->actingAs($this->user1);
        $response = $this->post(route('tag_review.post', [
            'tag_id' => $this->tag->id,
            'tag_review_id[1]' => 1,
            'modify_type[1]' => 'change',
            'anime_id[1]' => $this->anime->id,
            'score[1]' => 35,
            'comment[1]' => 'change'
        ]));
        $this->assertDatabaseHas('tag_reviews', [
            'anime_id' => $this->anime->id,
            'user_id' => $this->user1->id,
            'tag_id' => $this->tag->id,
            'score' => 35,
            'comment' => 'change',
        ]);
        $response->assertRedirect(route('tag.show', ['tag_id' => $this->tag->id]));
        $this->get(route('tag.show', ['tag_id' => $this->tag->id]))->assertSee('入力が完了しました。');
    }

    /**
     * ユーザーのタグレビュー追加タグ作成のテスト
     *
     * @test
     * @return void
     */
    public function testUser1TagReviewAddPost()
    {
        $this->actingAs($this->user1);
        $response = $this->post(route('tag_review.post', [
            'tag_id' => $this->tag->id,
            'anime_id[1]' => $this->anime->id,
            'tag_review_id[1]' => 1,
            'modify_type[1]' => 'no_change',
            'score[1]' => 35,
            'comment[1]' => 'no change',
            'modify_type[2]' => 'add',
            'anime_id[2]' => $this->anime2->id,
            'score[2]' => 35,
            'comment[2]' => 'add'
        ]));
        $this->assertDatabaseHas('tag_reviews', [
            'anime_id' => $this->anime2->id,
            'user_id' => $this->user1->id,
            'tag_id' => $this->tag->id,
            'score' => 35,
            'comment' => 'add',
        ]);
        $response->assertRedirect(route('tag.show', ['tag_id' => $this->tag->id]));
        $this->get(route('tag.show', ['tag_id' => $this->tag->id]))->assertSee('入力が完了しました。');
    }

    /**
     * ユーザーの同一タグレビュー追加のテスト
     *
     * @test
     * @return void
     */
    public function testUser1ExistTagReviewAddPost()
    {
        $this->actingAs($this->user1);
        $response = $this->post(route('tag_review.post', [
            'tag_id' => $this->tag->id,
            'anime_id[1]' => $this->anime->id,
            'tag_review_id[1]' => 1,
            'modify_type[1]' => 'no_change',
            'score[1]' => 35,
            'comment[1]' => 'no change',
            'modify_type[2]' => 'add',
            'anime_id[2]' => $this->anime->id,
            'score[2]' => 30,
            'comment[2]' => 'add'
        ]));
        $this->assertDatabaseMissing('tag_reviews', [
            'anime_id' => $this->anime->id,
            'user_id' => $this->user1->id,
            'tag_id' => $this->tag->id,
            'score' => 35,
            'comment' => 'add',
        ]);
        $response->assertRedirect(route('tag.show', ['tag_id' => $this->tag->id]));
        $this->get(route('tag.show', ['tag_id' => $this->tag->id]))->assertSee('入力が完了しました。');
    }

    /**
     * 存在しないアニメのタグレビュー入力のテスト
     *
     * @test
     * @return void
     */
    public function testUser1NotExistTagReviewPost()
    {
        $this->actingAs($this->user1);
        $response = $this->post(route('tag_review.post', [
            'tag_id' => 33333333333,
            'anime_id[1]' => $this->anime->id,
            'tag_review_id[1]' => 1,
            'modify_type[1]' => 'no_change',
            'score[1]' => 35,
            'comment[1]' => 'no change',
            'modify_type[2]' => 'add',
            'anime_id[2]' => $this->anime->id,
            'score[2]' => 35,
            'comment[2]' => 'add'
        ]));
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
