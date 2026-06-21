<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
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
        ];

        foreach ($colors as $key => $value) {
            Setting::updateOrCreate(
                ['group' => 'theme', 'key' => $key],
                ['value' => $value, 'type' => 'color']
            );
        }

        foreach (['logo', 'favicon'] as $key) {
            Setting::updateOrCreate(
                ['group' => 'theme', 'key' => $key],
                ['value' => null, 'media_id' => null, 'type' => 'media']
            );
        }

        $general = [
            'site_name' => 'Poised Technology',
            'site_tagline' => 'Delivering scalable software, cloud and EV solutions that power modern businesses.',
            'contact_phone' => '+012 345 6789',
            'contact_phone_secondary' => null,
            'contact_email' => 'info@example.com',
            'contact_email_secondary' => null,
            'address' => 'F-15, First Floor, Block D 242, Sector 63, Noida-201301',
            'business_hours' => 'Mon-Sat 09am-5pm, Sun Closed',
        ];

        foreach ($general as $key => $value) {
            Setting::updateOrCreate(
                ['group' => 'general', 'key' => $key],
                ['value' => $value, 'type' => 'text']
            );
        }

        $social = [
            'facebook_url' => null,
            'twitter_url' => null,
            'linkedin_url' => null,
            'instagram_url' => null,
            'youtube_url' => null,
        ];

        foreach ($social as $key => $value) {
            Setting::updateOrCreate(
                ['group' => 'social', 'key' => $key],
                ['value' => $value, 'type' => 'url']
            );
        }

        $seo = [
            'default_meta_title' => 'Poised Technology',
            'default_meta_description' => 'Poised Technology provides innovative IT solutions including software development, cloud infrastructure, data analytics and digital consulting.',
            'default_meta_keywords' => 'IT Consulting, Software Development, Cloud Solutions, Digital Transformation',
        ];

        foreach ($seo as $key => $value) {
            Setting::updateOrCreate(
                ['group' => 'seo', 'key' => $key],
                ['value' => $value, 'type' => 'text']
            );
        }

        $footer = [
            'copyright_text' => 'Poised. All Rights Reserved.',
        ];

        foreach ($footer as $key => $value) {
            Setting::updateOrCreate(
                ['group' => 'footer', 'key' => $key],
                ['value' => $value, 'type' => 'text']
            );
        }

        $scripts = [
            'header_scripts' => null,
            'footer_scripts' => null,
        ];

        foreach ($scripts as $key => $value) {
            Setting::updateOrCreate(
                ['group' => 'scripts', 'key' => $key],
                ['value' => $value, 'type' => 'script']
            );
        }
    }
}
