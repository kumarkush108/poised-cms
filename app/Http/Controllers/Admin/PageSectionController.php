<?php

namespace App\Http\Controllers\Admin;

use App\Cms\PageRevisionService;
use App\Cms\TemplateRegistry;
use App\Http\Controllers\Admin\Concerns\HandlesTemplateFields;
use App\Http\Controllers\Controller;
use App\Models\PageSection;
use Illuminate\Http\Request;

class PageSectionController extends Controller
{
    use HandlesTemplateFields;

    public function update(Request $request, PageSection $section)
    {
        $fieldDefs = TemplateRegistry::sectionFields($section->section_key);

        $this->normalizeMediaFields($request, $fieldDefs);

        $validated = $request->validate($this->templateFieldRules($fieldDefs));

        $this->syncTemplateFields($section, $fieldDefs, $validated);

        $section->update(['is_active' => $request->boolean('is_active')]);

        PageRevisionService::record($section->page, "Updated section \"{$section->section_key}\"");

        return back()->with('success', 'Section updated successfully.');
    }
}
