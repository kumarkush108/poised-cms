<?php

namespace App\Cms;

class TemplateRegistry
{
    public static function pageTemplates(bool $forNewPage = false): array
    {
        $templates = config('cms.templates.page_templates', []);

        if (! $forNewPage) {
            return $templates;
        }

        return array_filter($templates, fn (array $template) => empty($template['system_only']));
    }

    public static function pageTemplate(string $template): ?array
    {
        return config("cms.templates.page_templates.{$template}");
    }

    public static function allowedSections(string $template): array
    {
        return self::pageTemplate($template)['allowed_sections'] ?? [];
    }

    public static function section(string $sectionKey): ?array
    {
        return config("cms.templates.sections.{$sectionKey}");
    }

    public static function sectionFields(string $sectionKey): array
    {
        return self::section($sectionKey)['fields'] ?? [];
    }

    public static function itemSchema(string $sectionKey): ?array
    {
        return self::section($sectionKey)['items'] ?? null;
    }

    public static function itemFields(string $sectionKey): array
    {
        return self::itemSchema($sectionKey)['fields'] ?? [];
    }
}
