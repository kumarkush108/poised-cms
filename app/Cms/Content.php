<?php

namespace App\Cms;

use App\Models\Media;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\SectionItem;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Support\Collection;

/**
 * Small set of helpers shared by the public section partials
 * (resources/views/partials/sections/*) to read CMS field/item
 * values with graceful fallbacks to the original hardcoded content.
 */
class Content
{
    /**
     * Read a section_fields value, falling back to $default when the
     * section is missing or the field has no value.
     */
    public static function field(?PageSection $section, string $key, $default = null)
    {
        if (! $section) {
            return $default;
        }

        $value = $section->field($key);

        return ($value === null || $value === '') ? $default : $value;
    }

    /**
     * Read an item_fields value from either a real SectionItem or a
     * plain array (used for fallback/default item definitions).
     */
    public static function itemField($item, string $key, $default = null)
    {
        if ($item instanceof SectionItem) {
            $value = $item->field($key);

            return ($value === null || $value === '') ? $default : $value;
        }

        $value = $item[$key] ?? null;

        return ($value === null || $value === '') ? $default : $value;
    }

    /**
     * Return the section's active items, or a collection of default
     * (array-based) items when the section has none yet, or when its
     * items exist only as empty structural placeholders (no field
     * content has been entered for any of them yet).
     */
    public static function items(?PageSection $section, array $defaults): Collection
    {
        if ($section) {
            $items = $section->items->filter(fn ($item) => $item->is_active)->values();

            if ($items->isNotEmpty() && $items->contains(fn ($item) => $item->fields->isNotEmpty())) {
                return $items;
            }
        }

        return collect($defaults);
    }

    /**
     * Resolve a media-type field value (Media model or null) to a URL,
     * falling back to a static asset path when no media is set.
     */
    public static function mediaUrl($value, ?string $fallback = null): ?string
    {
        if ($value instanceof Media) {
            return $value->url;
        }

        return $fallback;
    }

    /**
     * Split a newline-delimited text field (e.g. service-card.highlights)
     * into a list of non-empty, trimmed lines.
     */
    public static function lines(?string $text): array
    {
        if (! $text) {
            return [];
        }

        return array_values(array_filter(
            array_map('trim', explode("\n", $text)),
            fn ($line) => $line !== ''
        ));
    }

    /**
     * Read a theme setting's value, falling back to $default when the
     * setting row is missing or empty.
     */
    public static function settingValue($settings, string $key, ?string $default = null): ?string
    {
        $setting = $settings->get($key);

        if (! $setting || ! $setting->value) {
            return $default;
        }

        return $setting->value;
    }

    /**
     * Resolve a media-type theme setting (e.g. logo/favicon) to a URL.
     */
    public static function settingMediaUrl($settings, string $key): ?string
    {
        $setting = $settings->get($key);

        if ($setting && $setting->media) {
            return $setting->media->url;
        }

        return null;
    }

    /**
     * Read a Page metadata column (meta_title, og_description, etc.),
     * falling back to $default when the page is missing or the column
     * is empty.
     */
    public static function pageMeta(?Page $page, string $column, ?string $default = null): ?string
    {
        if (! $page) {
            return $default;
        }

        $value = $page->{$column};

        return ($value === null || $value === '') ? $default : $value;
    }

    /**
     * Sanitize a richtext field value before rendering it with {!! !!}.
     * Strips script/event-handler/style-injection vectors while preserving
     * the basic formatting tags used by CMS body content (paragraphs,
     * headings, lists, links, emphasis, blockquotes).
     */
    public static function richtext(?string $html): string
    {
        if (! $html) {
            return '';
        }

        return self::purifier()->purify($html);
    }

    private static ?HTMLPurifier $purifier = null;

    private static function purifier(): HTMLPurifier
    {
        if (self::$purifier === null) {
            $config = HTMLPurifier_Config::createDefault();
            $config->set('HTML.Allowed', implode(',', [
                'p[class]', 'br', 'strong', 'em', 'b', 'i', 'u',
                'h1[class]', 'h2[class]', 'h3[class]', 'h4[class]', 'h5[class]', 'h6[class]',
                'ul[class]', 'ol[class]', 'li[class]',
                'a[href|title|target|rel]', 'blockquote', 'span[class]',
            ]));
            $config->set('HTML.TargetBlank', true);
            $config->set('Cache.SerializerPath', storage_path('framework/cache/purifier'));

            self::$purifier = new HTMLPurifier($config);
        }

        return self::$purifier;
    }
}
