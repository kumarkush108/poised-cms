<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Page;
use Illuminate\Database\Seeder;

class MenusSeeder extends Seeder
{
    public function run(): void
    {
        $header = Menu::firstOrCreate(['key' => 'header'], ['name' => 'Header Menu']);
        $footer = Menu::firstOrCreate(['key' => 'footer'], ['name' => 'Footer Menu']);
        $topbar = Menu::firstOrCreate(['key' => 'topbar'], ['name' => 'Topbar Menu']);

        $items = [
            ['slug' => 'home', 'label' => 'Home'],
            ['slug' => 'about', 'label' => 'About'],
            ['slug' => 'services', 'label' => 'Services'],
            ['slug' => 'solutions', 'label' => 'Solutions'],
            ['slug' => 'contact', 'label' => 'Contact'],
        ];

        foreach ([$header, $footer] as $menu) {
            foreach ($items as $index => $item) {
                $page = Page::where('slug', $item['slug'])->first();

                $menu->items()->firstOrCreate(
                    ['page_id' => $page->id],
                    [
                        'label' => $item['label'],
                        'order_column' => $index,
                        'is_active' => true,
                    ]
                );
            }
        }

        // Topbar quick-links: Career/Support/Terms/FAQs, resolved to the real
        // pages created by StandalonePagesSeeder (run just before this).
        $topbarItems = [
            ['slug' => 'career', 'label' => 'Career'],
            ['slug' => 'support', 'label' => 'Support'],
            ['slug' => 'terms', 'label' => 'Terms'],
            ['slug' => 'faq', 'label' => 'FAQs'],
        ];

        foreach ($topbarItems as $index => $item) {
            $page = Page::where('slug', $item['slug'])->first();

            if (! $page) {
                continue;
            }

            $topbar->items()->firstOrCreate(
                ['page_id' => $page->id],
                [
                    'label' => $item['label'],
                    'order_column' => $index,
                    'is_active' => true,
                ]
            );
        }
    }
}
