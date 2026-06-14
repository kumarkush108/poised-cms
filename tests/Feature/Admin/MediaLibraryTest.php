<?php

namespace Tests\Feature\Admin;

use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaLibraryTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_media_library(): void
    {
        $response = $this->get(route('admin.media.index'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_authenticated_user_can_upload_media(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('photo.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($user)
            ->post(route('admin.media.store'), ['file' => $file]);

        $response->assertRedirect();

        $media = Media::first();

        $this->assertNotNull($media);
        $this->assertSame('public', $media->disk);
        $this->assertSame('photo.jpg', $media->filename);
        Storage::disk('public')->assertExists($media->path);
    }

    public function test_upload_rejects_invalid_file_type(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('document.exe', 100, 'application/x-msdownload');

        $response = $this->actingAs($user)
            ->post(route('admin.media.store'), ['file' => $file]);

        $response->assertSessionHasErrors('file');
        $this->assertSame(0, Media::count());
    }

    public function test_upload_rejects_file_exceeding_max_size(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('large.jpg', 6000, 'image/jpeg');

        $response = $this->actingAs($user)
            ->post(route('admin.media.store'), ['file' => $file]);

        $response->assertSessionHasErrors('file');
        $this->assertSame(0, Media::count());
    }

    public function test_authenticated_user_can_update_media_details(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $media = Media::create([
            'disk' => 'public',
            'path' => 'media/photo.jpg',
            'filename' => 'photo.jpg',
            'mime_type' => 'image/jpeg',
            'size' => 1024,
        ]);

        $response = $this->actingAs($user)
            ->patch(route('admin.media.update', $media), [
                'alt_text' => 'A nice photo',
                'title' => 'Photo title',
            ]);

        $response->assertRedirect();

        $media->refresh();
        $this->assertSame('A nice photo', $media->alt_text);
        $this->assertSame('Photo title', $media->title);
    }

    public function test_destroy_soft_deletes_and_keeps_file_on_disk(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('media/photo.jpg', 'fake-contents');

        $user = User::factory()->create();
        $media = Media::create([
            'disk' => 'public',
            'path' => 'media/photo.jpg',
            'filename' => 'photo.jpg',
            'mime_type' => 'image/jpeg',
            'size' => 1024,
        ]);

        $response = $this->actingAs($user)
            ->delete(route('admin.media.destroy', $media));

        $response->assertRedirect();

        $this->assertSoftDeleted('media', ['id' => $media->id]);
        Storage::disk('public')->assertExists('media/photo.jpg');
    }

    public function test_force_delete_removes_file_from_storage(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('media/photo.jpg', 'fake-contents');

        $media = Media::create([
            'disk' => 'public',
            'path' => 'media/photo.jpg',
            'filename' => 'photo.jpg',
            'mime_type' => 'image/jpeg',
            'size' => 1024,
        ]);

        $media->forceDelete();

        $this->assertDatabaseMissing('media', ['id' => $media->id]);
        Storage::disk('public')->assertMissing('media/photo.jpg');
    }
}
