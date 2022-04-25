<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Contact;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    private Contact $contact1;
    private Contact $contact2;

    protected function setUp(): void
    {
        parent::setUp();
        $this->contact1 = Contact::factory()->create(['comment' => 'excellent']);
        $this->contact2 = Contact::factory()->create(['comment' => 'not sad']);
    }

    /**
     * 要望フォームの表示のテスト
     *
     * @test
     * @return void
     */
    public function testContactView()
    {
        $response = $this->get('/contact');
        $response->assertStatus(200);
    }

    /**
     * 要望フォームのコメント表示のテスト
     *
     * @test
     * @return void
     */
    public function testContactCommentView()
    {
        $response = $this->get('/contact');
        $response->assertSeeInOrder([
            $this->contact1->name,
            $this->contact1->comment,
            $this->contact2->name,
            $this->contact2->comment,
        ]);
    }

    /**
     * 要望フォームの名前入力時の入力のテスト
     *
     * @test
     * @return void
     */
    public function testContactNamePost()
    {
        $response = $this->post('/contact', [
            'name' => 'user',
            'comment' => 'exellent',
            'auth' => 'にんしょう',
        ]);
        $this->assertDatabaseHas('contacts', [
            'name' => 'user',
            'comment' => 'exellent',
        ]);
        $response->assertRedirect('/contact');
    }

    /**
     * 要望フォームの名無し時の入力のテスト
     *
     * @test
     * @return void
     */
    public function testContactNanashiPost()
    {
        $response = $this->post('/contact', [
            'comment' => 'exellent',
            'auth' => 'にんしょう',
        ]);

        $this->assertDatabaseHas('contacts', [
            'name' => '名無しさん',
            'comment' => 'exellent',
        ]);
    }

    /**
     * 要望フォームのバリデーションのテスト
     *
     * @test
     * @return void
     */
    public function testContactPostValidation()
    {
        $this->post('/contact', [
            'name' => 'user',
            'auth' => 'にんしょ',
        ]);
        $this->get(route('contact.post'))->assertSeeInOrder([
            '要望内容を入力してください。',
            '「にんしょう」と入力してください。'
        ]);
    }
}
