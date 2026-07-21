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
        $response->assertSee('Website Settings');
        $response->assertSee('#0d6efd');
        $response->assertSee('Contact Information');
        $response->assertSee('Social Media Links');
        $response->assertSee('SEO Defaults');
        $response->assertSee('Custom Scripts');
    }

    public function test_authenticated_user_can_update_colors(): void
    {
        $user = User::factory()->create();

        $payload = $this->validPayload(['primary_color' => '#ff0000']);

        $response = $this->actingAs($user)
            ->patch(route('admin.settings.update'), $payload);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

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

    public function test_all_fourteen_colors_can_be_saved(): void
    {
        $user = User::factory()->create();

        $payload = $this->validPayload(['success_color' => '#00ff00', 'light_color' => '#eeeeee']);

        $this->actingAs($user)->patch(route('admin.settings.update'), $payload)
            ->assertSessionHasNoErrors();

        $this->assertSame('#00ff00', Setting::where('group', 'theme')->where('key', 'success_color')->first()->value);
        $this->assertSame('#eeeeee', Setting::where('group', 'theme')->where('key', 'light_color')->first()->value);
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
        $response->assertSessionHasNoErrors();

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

    public function test_site_name_is_required(): void
    {
        $user = User::factory()->create();

        $payload = $this->validPayload(['site_name' => '']);

        $response = $this->actingAs($user)
            ->patch(route('admin.settings.update'), $payload);

        $response->assertSessionHasErrors('site_name');
    }

    public function test_authenticated_user_can_update_contact_and_general_info(): void
    {
        $user = User::factory()->create();

        $payload = $this->validPayload([
            'site_name' => 'Acme Corp',
            'site_tagline' => 'We build things.',
            'contact_phone' => '+1 555 0100',
            'contact_email' => 'hello@acme.test',
            'address' => '1 Acme Way',
            'business_hours' => '24/7',
        ]);

        $this->actingAs($user)->patch(route('admin.settings.update'), $payload)
            ->assertSessionHasNoErrors();

        $this->assertSame('Acme Corp', Setting::where('group', 'general')->where('key', 'site_name')->first()->value);
        $this->assertSame('hello@acme.test', Setting::where('group', 'general')->where('key', 'contact_email')->first()->value);
    }

    public function test_invalid_contact_email_is_rejected(): void
    {
        $user = User::factory()->create();

        $payload = $this->validPayload(['contact_email' => 'not-an-email']);

        $this->actingAs($user)->patch(route('admin.settings.update'), $payload)
            ->assertSessionHasErrors('contact_email');
    }

    public function test_authenticated_user_can_update_social_links(): void
    {
        $user = User::factory()->create();

        $payload = $this->validPayload(['facebook_url' => 'https://facebook.com/acme']);

        $this->actingAs($user)->patch(route('admin.settings.update'), $payload)
            ->assertSessionHasNoErrors();

        $this->assertSame('https://facebook.com/acme', Setting::where('group', 'social')->where('key', 'facebook_url')->first()->value);
    }

    public function test_invalid_social_url_is_rejected(): void
    {
        $user = User::factory()->create();

        $payload = $this->validPayload(['facebook_url' => 'not-a-url']);

        $this->actingAs($user)->patch(route('admin.settings.update'), $payload)
            ->assertSessionHasErrors('facebook_url');
    }

    public function test_authenticated_user_can_update_seo_defaults(): void
    {
        $user = User::factory()->create();

        $payload = $this->validPayload(['default_meta_title' => 'Acme — Build Better']);

        $this->actingAs($user)->patch(route('admin.settings.update'), $payload)
            ->assertSessionHasNoErrors();

        $this->assertSame('Acme — Build Better', Setting::where('group', 'seo')->where('key', 'default_meta_title')->first()->value);
    }

    public function test_authenticated_user_can_update_footer_copyright_text(): void
    {
        $user = User::factory()->create();

        $payload = $this->validPayload(['copyright_text' => 'Acme Corp. All Rights Reserved.']);

        $this->actingAs($user)->patch(route('admin.settings.update'), $payload)
            ->assertSessionHasNoErrors();

        $this->assertSame('Acme Corp. All Rights Reserved.', Setting::where('group', 'footer')->where('key', 'copyright_text')->first()->value);
    }

    public function test_authenticated_user_can_update_custom_scripts(): void
    {
        $user = User::factory()->create();

        $payload = $this->validPayload(['header_scripts' => '<script>console.log("hi")</script>']);

        $this->actingAs($user)->patch(route('admin.settings.update'), $payload)
            ->assertSessionHasNoErrors();

        $this->assertSame(
            '<script>console.log("hi")</script>',
            Setting::where('group', 'scripts')->where('key', 'header_scripts')->first()->value
        );
    }

    /**
     * Build a valid update payload (all 14 colors + general/social/seo/footer/scripts
     * fields + nullable media ids), with overrides.
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
            'success_color' => '#198754',
            'warning_color' => '#ffc107',
            'danger_color' => '#dc3545',
            'dark_color' => '#212529',
            'light_color' => '#f8f9fa',
            'logo_media_id' => null,
            'favicon_media_id' => null,
            'site_name' => 'Poised Technology',
            'site_tagline' => null,
            'contact_phone' => null,
            'contact_phone_secondary' => null,
            'contact_email' => null,
            'contact_email_secondary' => null,
            'address' => null,
            'business_hours' => null,
            'facebook_url' => null,
            'twitter_url' => null,
            'linkedin_url' => null,
            'instagram_url' => null,
            'youtube_url' => null,
            'default_meta_title' => null,
            'default_meta_description' => null,
            'default_meta_keywords' => null,
            'copyright_text' => null,
            'header_scripts' => null,
            'footer_scripts' => null,
        ];

        return array_merge($base, $overrides);
    }
}
