<?php

namespace Tests\Feature\Admin;

use App\Models\ContentMedia;
use App\Models\Media;
use App\Models\NewsArticle;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentMediaManagementTest extends TestCase
{
    use RefreshDatabase;

    private function makeMedia(string $filename = 'image.png'): Media
    {
        return Media::create([
            'disk' => 'public', 'path' => 'media/' . $filename, 'filename' => $filename,
            'mime_type' => 'image/png', 'size' => 1024,
        ]);
    }

    public function test_gallery_image_can_be_attached_to_a_product(): void
    {
        $user = User::factory()->create();
        $product = Product::create(['slug' => 'smart-charger', 'title' => 'Smart Charger', 'status' => 'draft']);
        $media = $this->makeMedia();

        $response = $this->actingAs($user)->post(route('admin.content-media.store', ['type' => 'product', 'id' => $product->id]), [
            'media_id' => $media->id,
            'role' => 'gallery',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('content_media', [
            'mediable_type' => 'product', 'mediable_id' => $product->id, 'media_id' => $media->id, 'role' => 'gallery',
        ]);
        $this->assertCount(1, $product->gallery);
    }

    public function test_document_can_be_attached_to_a_news_article(): void
    {
        $user = User::factory()->create();
        $article = NewsArticle::create(['slug' => 'first-article', 'title' => 'First Article', 'status' => 'draft']);
        $media = $this->makeMedia('brochure.pdf');

        $this->actingAs($user)->post(route('admin.content-media.store', ['type' => 'news_article', 'id' => $article->id]), [
            'media_id' => $media->id,
            'role' => 'document',
        ])->assertRedirect();

        $this->assertCount(1, $article->documents);
    }

    public function test_unrecognized_type_is_rejected(): void
    {
        $user = User::factory()->create();
        $media = $this->makeMedia();

        $response = $this->actingAs($user)->post('/admin/content-media/not-a-type/1', [
            'media_id' => $media->id,
            'role' => 'gallery',
        ]);

        $response->assertNotFound();
    }

    public function test_content_media_item_can_be_removed(): void
    {
        $user = User::factory()->create();
        $product = Product::create(['slug' => 'smart-charger', 'title' => 'Smart Charger', 'status' => 'draft']);
        $media = $this->makeMedia();

        $contentMedia = ContentMedia::create([
            'mediable_type' => 'product', 'mediable_id' => $product->id, 'media_id' => $media->id, 'role' => 'gallery',
        ]);

        $this->actingAs($user)->delete(route('admin.content-media.destroy', $contentMedia))
            ->assertRedirect();

        $this->assertDatabaseMissing('content_media', ['id' => $contentMedia->id]);
    }
}
