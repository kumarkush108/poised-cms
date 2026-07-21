<?php

namespace App\Cms;

use App\Models\Page;

/**
 * Creates the structural page_sections rows for a brand-new page, based on
 * its template's allowed_sections. Unlike PagesSeeder (which also stubs out
 * section_items to match pre-existing hardcoded markup on the 5 system
 * pages), a newly admin-created page has no existing markup to match, so no
 * items are pre-created — the admin adds them via the existing "Add Item" UI.
 */
class PageSectionBootstrapper
{
    public static function run(Page $page): void
    {
        foreach (TemplateRegistry::allowedSections($page->template) as $index => $sectionKey) {
            $page->sections()->firstOrCreate(
                ['section_key' => $sectionKey],
                ['order_column' => $index, 'is_active' => true]
            );
        }
    }
}
