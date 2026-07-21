<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Two section types were shared by templates whose pages render different
     * subsets of their fields, letting the admin show inputs (badge value/label
     * on every page using 'content'; button/video/subheading on every page
     * using 'ev_solutions') that silently had no effect on most of those pages'
     * frontend — exactly the mismatch reported between /admin/pages/3 and
     * /service. This splits the About page's two affected sections onto their
     * own lean types ('about_intro', 'ev_highlights') that only carry the
     * fields About's own view actually renders, while Services/Solutions/Contact
     * keep the original 'content' type unchanged, and Home keeps the full
     * 'ev_solutions' type unchanged.
     */
    public function up(): void
    {
        $aboutPageId = DB::table('pages')->where('slug', 'about')->value('id');

        if (! $aboutPageId) {
            return;
        }

        DB::table('page_sections')
            ->where('page_id', $aboutPageId)
            ->where('section_key', 'content')
            ->update(['section_key' => 'about_intro']);

        DB::table('page_sections')
            ->where('page_id', $aboutPageId)
            ->where('section_key', 'ev_solutions')
            ->update(['section_key' => 'ev_highlights']);

        // Clean up orphaned badge data that was entered on other pages' shared
        // 'content' section before this split (the literal bug being fixed —
        // confirmed present on the Services page's content.badge_value/badge_label).
        $contentSectionIds = DB::table('page_sections')
            ->where('section_key', 'content')
            ->pluck('id');

        DB::table('section_fields')
            ->whereIn('page_section_id', $contentSectionIds)
            ->whereIn('field_key', ['badge_value', 'badge_label'])
            ->delete();

        // 'checklist.heading' is removed from the schema in this same change
        // (no consuming page has anywhere to render it — checklist items are
        // always nested under another section's own heading). Clean up any
        // value an admin may have entered for it.
        $checklistSectionIds = DB::table('page_sections')
            ->where('section_key', 'checklist')
            ->pluck('id');

        DB::table('section_fields')
            ->whereIn('page_section_id', $checklistSectionIds)
            ->where('field_key', 'heading')
            ->delete();
    }

    public function down(): void
    {
        $aboutPageId = DB::table('pages')->where('slug', 'about')->value('id');

        if (! $aboutPageId) {
            return;
        }

        DB::table('page_sections')
            ->where('page_id', $aboutPageId)
            ->where('section_key', 'about_intro')
            ->update(['section_key' => 'content']);

        DB::table('page_sections')
            ->where('page_id', $aboutPageId)
            ->where('section_key', 'ev_highlights')
            ->update(['section_key' => 'ev_solutions']);
    }
};
