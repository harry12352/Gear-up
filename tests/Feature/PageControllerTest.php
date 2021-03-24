<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_page_is_viewable()
    {
        $this->withoutExceptionHandling();
        $page = factory(\App\Models\Page::class)->create();
        $response = $this->get('/page/' . $page['slug']);
        $response->assertViewIs('pages.show');

    }
}
