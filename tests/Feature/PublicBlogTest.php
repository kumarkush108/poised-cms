<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicBlogTest extends TestCase
{
    use RefreshDatabase;

    public function test_blog_index_only_shows_published_posts(): void
    {
        BlogPost::create(['slug' => 'published-one', 'title' => 'Published One', 'status' => 'published', 'published_at' => now()]);
        BlogPost::create(['slug' => 'draft-one', 'title' => 'Draft One', 'status' => 'draft']);

        $response = $this->get(route('blog.index'));

        $response->assertOk();
        $response->assertSee('Published One');
        $response->assertDontSee('Draft One');
    }

    public function test_draft_post_detail_page_returns_404(): void
    {
        $post = BlogPost::create(['slug' => 'draft-one', 'title' => 'Draft One', 'status' => 'draft']);

        $this->get(route('blog.show', $post->slug))->assertNotFound();
    }

    public function test_published_post_detail_page_shows_content_and_reading_time(): void
    {
        $post = BlogPost::create([
            'slug' => 'first-post', 'title' => 'First Post',
            'body' => '<p>' . str_repeat('word ', 400) . '</p>',
            'author_name' => 'Jane Doe', 'status' => 'published', 'published_at' => now(),
        ]);

        $response = $this->get(route('blog.show', $post->slug));

        $response->assertOk();
        $response->assertSee('First Post');
        $response->assertSee('Jane Doe');
        $response->assertSee('2 min read');
    }

    public function test_prev_and_next_navigation_resolves_by_published_date(): void
    {
        $older = BlogPost::create(['slug' => 'older', 'title' => 'Older Post', 'status' => 'published', 'published_at' => now()->subDays(2)]);
        $current = BlogPost::create(['slug' => 'current', 'title' => 'Current Post', 'status' => 'published', 'published_at' => now()->subDay()]);
        $newer = BlogPost::create(['slug' => 'newer', 'title' => 'Newer Post', 'status' => 'published', 'published_at' => now()]);

        $response = $this->get(route('blog.show', $current->slug));

        $response->assertOk();
        $response->assertSee('Older Post');
        $response->assertSee('Newer Post');
    }
}
