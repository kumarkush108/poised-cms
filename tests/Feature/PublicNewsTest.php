<?php

namespace Tests\Feature;

use App\Models\NewsArticle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicNewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_news_index_only_shows_published_articles(): void
    {
        NewsArticle::create(['slug' => 'published-one', 'title' => 'Published One', 'status' => 'published', 'published_at' => now()]);
        NewsArticle::create(['slug' => 'draft-one', 'title' => 'Draft One', 'status' => 'draft']);

        $response = $this->get(route('news.index'));

        $response->assertOk();
        $response->assertSee('Published One');
        $response->assertDontSee('Draft One');
    }

    public function test_draft_article_detail_page_returns_404(): void
    {
        $article = NewsArticle::create(['slug' => 'draft-one', 'title' => 'Draft One', 'status' => 'draft']);

        $this->get(route('news.show', $article->slug))->assertNotFound();
    }

    public function test_published_article_detail_page_shows_content(): void
    {
        $article = NewsArticle::create([
            'slug' => 'first-article', 'title' => 'First Article',
            'body' => '<p>Article body content.</p>', 'status' => 'published', 'published_at' => now(),
        ]);

        $response = $this->get(route('news.show', $article->slug));

        $response->assertOk();
        $response->assertSee('First Article');
        $response->assertSee('Article body content.');
    }
}
