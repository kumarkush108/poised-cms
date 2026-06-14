<?php

namespace Tests\Feature\Admin;

use App\Models\Media;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\SettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        (new SettingsSeeder())->run();
    }

    public function test_guest_is_redirected_from_settings(): void
    {
        $response = $this->get(route('admin.settings.index'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_authenticated_user_can_view_settings(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.settings.index'));

        $response->assertOk();
        $response->assertSee('Theme Settings');
        $response->assertSee('#0d6efd');
    }

    public function test_authenticated_user_can_update_colors(): void
    {
        $user = User::factory()->create();

        $payload = $this->validPayload(['primary_color' => '#ff0000']);

        $response = $this->actingAs($user)
            ->patch(route('admin.settings.update'), $payload);

        $response->assertRedirect();

        $this->assertSame('#ff0000', Setting::where('group', 'theme')
            ->where('key', 'primary_color')->first()->value);
    }

    public function test_update_rejects_invalid_hex_color(): void
    {
        $user = User::factory()->create();

        $payload = $this->validPayload(['primary_color' => 'not-a-color']);

        $response = $this->actingAs($user)
            ->patch(route('admin.settings.update'), $payload);

        $response->assertSessionHasErrors('primary_color');
    }

    public function test_authenticated_user_can_set_logo_to_existing_image_media(): void
    {
        $user = User::factory()->create();

        $logo = Media::create([
            'disk' => 'public',
            'path' => 'media/logo.png',
            'filename' => 'logo.png',
            'mime_type' => 'image/png',
            'size' => 1024,
        ]);

        $payload = $this->validPayload(['logo_media_id' => $logo->id]);

        $response = $this->actingAs($user)
            ->patch(route('admin.settings.update'), $payload);

        $response->assertRedirect();

        $this->assertSame($logo->id, Setting::where('group', 'theme')
            ->where('key', 'logo')->first()->media_id);
    }

    public function test_update_rejects_non_existent_media_id_for_logo(): void
    {
        $user = User::factory()->create();

        $payload = $this->validPayload(['logo_media_id' => 9999]);

        $response = $this->actingAs($user)
            ->patch(route('admin.settings.update'), $payload);

        $response->assertSessionHasErrors('logo_media_id');
    }

    public function test_update_rejects_non_image_media_id_for_favicon(): void
    {
        $user = User::factory()->create();

        $pdf = Media::create([
            'disk' => 'public',
            'path' => 'media/document.pdf',
            'filename' => 'document.pdf',
            'mime_type' => 'application/pdf',
            'size' => 2048,
        ]);

        $payload = $this->validPayload(['favicon_media_id' => $pdf->id]);

        $response = $this->actingAs($user)
            ->patch(route('admin.settings.update'), $payload);

        $response->assertSessionHasErrors('favicon_media_id');
    }

    /**
     * Build a valid update payload (all 9 colors + nullable media ids), with overrides.
     */
    private function validPayload(array $overrides = []): array
    {
        $base = [
            'primary_color' => '#0d6efd',
            'secondary_color' => '#6c757d',
            'accent_color' => '#fd7e14',
            'header_background' => '#ffffff',
            'footer_background' => '#212529',
            'button_color' => '#0d6efd',
            'button_hover_color' => '#0b5ed7',
            'text_color' => '#212529',
            'link_color' => '#0d6efd',
            'logo_media_id' => null,
            'favicon_media_id' => null,
        ];

        return array_merge($base, $overrides);
    }
}
