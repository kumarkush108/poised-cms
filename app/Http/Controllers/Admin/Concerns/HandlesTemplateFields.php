<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Http\Request;

trait HandlesTemplateFields
{
    /**
     * Build validation rules for a set of TemplateRegistry field definitions,
     * keyed as "fields.{field_key}".
     */
    protected function templateFieldRules(array $fieldDefs): array
    {
        $rules = [];

        foreach ($fieldDefs as $key => $def) {
            $presence = ($def['required'] ?? false) ? 'required' : 'nullable';

            $rules["fields.$key"] = match ($def['type'] ?? 'string') {
                'text', 'richtext' => [$presence, 'string'],
                'url' => [$presence, 'url', 'max:255'],
                'integer' => [$presence, 'integer'],
                'media' => [$presence, 'exists:media,id'],
                default => [$presence, 'string', 'max:255'],
            };
        }

        return $rules;
    }

    /**
     * HTML "— None —" media selects submit an empty string rather than
     * omitting the key, which would fail "exists" validation even with
     * "nullable". Normalize empty media selections to null before validating.
     */
    protected function normalizeMediaFields(Request $request, array $fieldDefs): void
    {
        $fields = $request->input('fields', []);

        foreach ($fieldDefs as $key => $def) {
            if (($def['type'] ?? null) === 'media' && array_key_exists($key, $fields) && $fields[$key] === '') {
                $fields[$key] = null;
            }
        }

        $request->merge(['fields' => $fields]);
    }

    /**
     * Persist validated "fields.*" values onto a PageSection/SectionItem's
     * field rows, keyed by field_key (creating rows on first save).
     */
    protected function syncTemplateFields($model, array $fieldDefs, array $validated): void
    {
        foreach ($fieldDefs as $key => $def) {
            $value = $validated['fields'][$key] ?? null;

            $attrs = ($def['type'] ?? 'string') === 'media'
                ? ['value' => null, 'media_id' => $value !== null && $value !== '' ? $value : null]
                : ['value' => $value, 'media_id' => null];

            $model->fields()->updateOrCreate(['field_key' => $key], $attrs);
        }
    }
}
