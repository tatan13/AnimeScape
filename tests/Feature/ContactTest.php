<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

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
     * 要望フォームの入力のテスト
     *
     * @test
     * @return void
     */
    public function testContactPost()
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

        $check = array('user', 'exellent');
        $this->get(route('contact.post'))->assertSeeInOrder($check);
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
        $response = $this->post('/contact', [
            'name' => 'user',
            'auth' => 'にんしょ',
        ]);

        $validation = array( '要望内容を入力してください。', '「にんしょう」と入力してください。');
        $this->get(route('contact.post'))->assertSeeInOrder($validation);
    }
}
