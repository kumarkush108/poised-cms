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
    }
}
