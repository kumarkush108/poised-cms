<?php

namespace Tests\Feature\Admin;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogPostManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_blog_posts_index(): void
    {
        $this->get(route('admin.blog-posts.index'))->assertRedirect(route('admin.login'));
    }

    public function test_authenticated_user_can_create_a_post(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.blog-posts.store'), [
            'slug' => 'first-post',
            'title' => 'First Post',
            'status' => 'draft',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('blog_posts', ['slug' => 'first-post', 'title' => 'First Post']);
    }

    public function test_slug_cannot_be_changed_on_update(): void
    {
        $user = User::factory()->create();
        $post = BlogPost::create(['slug' => 'first-post', 'title' => 'First Post', 'status' => 'draft']);

        $this->actingAs($user)->patch(route('admin.blog-posts.update', $post), [
            'title' => 'Updated Title',
            'status' => 'published',
        ])->assertSessionHasNoErrors();

        $post->refresh();
        $this->assertSame('first-post', $post->slug);
        $this->assertSame('Updated Title', $post->title);
    }

    public function test_tags_are_created_and_synced_from_comma_separated_input(): void
    {
        $user = User::factory()->create();
        $post = BlogPost::create(['slug' => 'first-post', 'title' => 'First Post', 'status' => 'draft']);

        $this->actingAs($user)->patch(route('admin.blog-posts.update', $post), [
            'title' => 'First Post',
            'status' => 'draft',
            'tags' => 'EV, Technology',
        ])->assertSessionHasNoErrors();

        $tagNames = $post->tags()->pluck('name')->sort()->values()->all();
        $this->assertSame(['EV', 'Technology'], $tagNames);
    }

    public function test_blog_category_can_be_created_and_assigned(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('admin.blog-categories.store'), [
            'slug' => 'tech-news', 'name' => 'Tech News',
        ])->assertSessionHasNoErrors();

        $category = BlogCategory::where('slug', 'tech-news')->first();

        $this->actingAs($user)->post(route('admin.blog-posts.store'), [
            'slug' => 'first-post', 'title' => 'First Post', 'category_id' => $category->id, 'status' => 'draft',
        ])->assertSessionHasNoErrors();

        $this->assertSame($category->id, BlogPost::where('slug', 'first-post')->first()->category_id);
    }

    public function test_deleting_a_post_soft_deletes_it(): void
    {
        $user = User::factory()->create();
        $post = BlogPost::create(['slug' => 'first-post', 'title' => 'First Post', 'status' => 'published']);

        $this->actingAs($user)->delete(route('admin.blog-posts.destroy', $post))
            ->assertRedirect(route('admin.blog-posts.index'));

        $this->assertSoftDeleted('blog_posts', ['id' => $post->id]);
    }
}
