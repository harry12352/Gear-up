<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsLetterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_username_and_email_is_stored_for_newsletter()
    {
        $response = $this->post('/newsletter', ['email' => 'haroonzaib.gcu@gmail.co']);
        $this->assertEquals('You have been subscribed to our NewsLetter Successfully, Thanks', $response['message']);
    }

    public function test_error_is_thrown_on_duplicate_emails()
    {
        factory(\App\Models\NewsLetter::class)->create(['email' => 'haroonzaib.gcu@gmail.com']);
        $response = $this->post('/newsletter', ['email' => 'haroonzaib.gcu@gmail.com']);
        $this->assertEquals('Sorry!! Kindly enter other email address', $response['message']);
    }

    public function test_error_is_thrown_on_required_fields()
    {
        $response = $this->post('/newsletter', ['email' => '']);
        $response->assertStatus(302);
    }
}
