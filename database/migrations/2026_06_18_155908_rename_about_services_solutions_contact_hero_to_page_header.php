<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The 'hero' section type carries repeatable carousel slides, but only the
     * home page template actually renders them. About/Services/Solutions/Contact
     * pages reused 'hero' purely for a static page-header banner (heading,
     * subheading, background_image), which made the admin UI offer slide
     * management that those pages never use. This renames their existing
     * page_sections.section_key from 'hero' to the new 'page_header' section
     * type so already-seeded/edited content keeps its values.
     */
    public function up(): void
    {
        DB::table('page_sections')
            ->whereIn('page_id', DB::table('pages')->where('slug', '!=', 'home')->pluck('id'))
            ->where('section_key', 'hero')
            ->update(['section_key' => 'page_header']);
    }

    public function down(): void
    {
        DB::table('page_sections')
            ->whereIn('page_id', DB::table('pages')->where('slug', '!=', 'home')->pluck('id'))
            ->where('section_key', 'page_header')
            ->update(['section_key' => 'hero']);
    }
};
