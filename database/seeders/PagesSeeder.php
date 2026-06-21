<?php

namespace Database\Seeders;

use App\Cms\TemplateRegistry;
use App\Models\Page;
use Illuminate\Database\Seeder;

class PagesSeeder extends Seeder
{
    /**
     * Structural item counts per page/section, matching the number of
     * repeatable blocks (carousel slides, cards, stats, etc.) present in
     * the existing public Blade markup. Only structural `section_items`
     * rows (item_type/order_column/is_active) are created here — field
     * content is populated later via the Phase F admin UI (Phase I).
     */
    private const ITEM_COUNTS = [
        'home' => [
            'hero' => 3,
            'ev_solutions' => 3,
            'stats' => 4,
            'brand_logos' => 3,
            'about' => 3,
            'features' => 4,
            'tech_highlights' => 2,
            'skill_bars' => 3,
            'services_grid' => 8,
            'testimonials' => 2,
        ],
        'about' => [
            'checklist' => 2,
            'cards' => 2,
            'ev_highlights' => 2,
            'stats' => 4,
            'features' => 3,
        ],
        'services' => [
            'checklist' => 4,
            'services_grid' => 6,
            'features' => 4,
            'stats' => 4,
        ],
        'solutions' => [
            'checklist' => 4,
            'services_grid' => 6,
            'process_steps' => 4,
        ],
        'contact' => [
            'cards' => 2,
            'faq' => 3,
        ],
    ];

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
                $section = $page->sections()->firstOrCreate(
                    ['section_key' => $sectionKey],
                    ['order_column' => $index, 'is_active' => true]
                );

                // Re-sync display order on every run: allowed_sections can be
                // reordered/extended after a page's sections already exist
                // (e.g. inserting a new section type between two existing
                // ones), which would otherwise leave a stale order_column
                // that ties with - or follows - sections it should precede.
                if ($section->order_column !== $index) {
                    $section->update(['order_column' => $index]);
                }

                $itemType = TemplateRegistry::itemSchema($sectionKey)['item_type'] ?? null;
                $itemCount = self::ITEM_COUNTS[$pageData['slug']][$sectionKey] ?? 0;

                if (! $itemType || $itemCount === 0) {
                    continue;
                }

                for ($i = 0; $i < $itemCount; $i++) {
                    $section->items()->firstOrCreate(
                        ['order_column' => $i],
                        ['item_type' => $itemType, 'is_active' => true]
                    );
                }
            }
        }
    }
}
