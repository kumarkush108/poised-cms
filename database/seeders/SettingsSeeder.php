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
    }
}
