<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test 
    */
    public function test_contact_view()
    {
        $response = $this->get('/contact');

        $response->assertStatus(200);
    }

    /**
    * @test 
    */
    public function test_contact_post()
    {
        $response = $this->post('/contact',[
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
    * @test 
    */
    public function test_contact_nanashi_post()
    {
        $response = $this->post('/contact',[
            'comment' => 'exellent',
            'auth' => 'にんしょう',
        ]);

        $this->assertDatabaseHas('contacts', [
            'name' => '名無しさん',
            'comment' => 'exellent',
        ]);
    }

    /**
    * @test 
    */
    public function test_contact_post_validation()
    {
        $response = $this->post('/contact',[
            'name' => 'user',
            'auth' => 'にんしょ',
        ]);

        $validation = array( '要望内容を入力してください。', '「にんしょう」と入力してください。');
        $this->get(route('contact.post'))->assertSeeInOrder($validation);
    }
}
