<?php

namespace Database\Seeders;

use App\Cms\TemplateRegistry;
use App\Models\Page;
use Illuminate\Database\Seeder;

class PagesSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            ['slug' => 'home', 'title' => 'Home', 'template' => 'home'],
            ['slug' => 'about', 'title' => 'About', 'template' => 'standard_page'],
            ['slug' => 'services', 'title' => 'Services', 'template' => 'service_page'],
            ['slug' => 'solutions', 'title' => 'Solutions', 'template' => 'landing_page'],
            ['slug' => 'contact', 'title' => 'Contact', 'template' => 'contact_page'],
        ];

        foreach ($pages as $pageData) {
            $page = Page::firstOrCreate(
                ['slug' => $pageData['slug']],
                [
                    'title' => $pageData['title'],
                    'template' => $pageData['template'],
                    'is_system' => true,
                    'status' => 'published',
                    'published_at' => now(),
                ]
            );

            foreach (TemplateRegistry::allowedSections($pageData['template']) as $index => $sectionKey) {
                $page->sections()->firstOrCreate(
                    ['section_key' => $sectionKey],
                    ['order_column' => $index, 'is_active' => true]
                );
            }
        }
    }
}
