<?php

namespace Tests\Feature\Admin;

use App\Models\NewsArticle;
use App\Models\NewsCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsArticleManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_news_index(): void
    {
        $this->get(route('admin.news-articles.index'))->assertRedirect(route('admin.login'));
    }

    public function test_authenticated_user_can_create_an_article(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.news-articles.store'), [
            'slug' => 'first-article',
            'title' => 'First Article',
            'status' => 'draft',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('news_articles', ['slug' => 'first-article', 'title' => 'First Article']);
    }

    public function test_slug_cannot_be_changed_on_update(): void
    {
        $user = User::factory()->create();
        $article = NewsArticle::create(['slug' => 'first-article', 'title' => 'First Article', 'status' => 'draft']);

        $this->actingAs($user)->patch(route('admin.news-articles.update', $article), [
            'title' => 'Updated Title',
            'status' => 'published',
        ])->assertSessionHasNoErrors();

        $article->refresh();
        $this->assertSame('first-article', $article->slug);
        $this->assertSame('Updated Title', $article->title);
    }

    public function test_news_category_can_be_created_and_assigned(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('admin.news-categories.store'), [
            'slug' => 'company-news', 'name' => 'Company News',
        ])->assertSessionHasNoErrors();

        $category = NewsCategory::where('slug', 'company-news')->first();

        $this->actingAs($user)->post(route('admin.news-articles.store'), [
            'slug' => 'first-article', 'title' => 'First Article', 'category_id' => $category->id, 'status' => 'draft',
        ])->assertSessionHasNoErrors();

        $this->assertSame($category->id, NewsArticle::where('slug', 'first-article')->first()->category_id);
    }

    public function test_deleting_an_article_soft_deletes_it(): void
    {
        $user = User::factory()->create();
        $article = NewsArticle::create(['slug' => 'first-article', 'title' => 'First Article', 'status' => 'published']);

        $this->actingAs($user)->delete(route('admin.news-articles.destroy', $article))
            ->assertRedirect(route('admin.news-articles.index'));

        $this->assertSoftDeleted('news_articles', ['id' => $article->id]);
    }
}
