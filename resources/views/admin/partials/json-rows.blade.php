{{--
    Repeatable rows editor backed by a JSON array column (e.g. Product
    features/specifications) — no separate table/controller, submitted as
    part of the parent form. Expected variables:
    - $name: form field base name, e.g. 'features' or 'specifications'
    - $rows: current array of associative arrays
    - $fields: ordered [['key'=>,'label'=>,'type'=>'text'|'textarea'|'icon'], ...]
    - $addLabel: button text, e.g. "Add Feature"
--}}

@php
    $renderRowField = function (string $rowName, string $fieldKey, string $label, string $type, $value) {
        $inputName = "{$rowName}[{$fieldKey}]";

        if ($type === 'textarea') {
            return '<textarea class="form-control form-control-sm" name="' . $inputName . '" placeholder="' . e($label) . '" rows="2">' . e($value) . '</textarea>';
        }

        if ($type === 'icon') {
            $previewClass = $value !== '' ? 'bi ' . e($value) : 'bi bi-question-circle text-muted';

            return '<div class="icon-field input-group input-group-sm">'
                . '<span class="input-group-text icon-preview" data-icon-preview><i class="' . $previewClass . '"></i></span>'
                . '<input type="text" class="form-control form-control-sm" name="' . $inputName . '" placeholder="' . e($label) . '" value="' . e($value) . '" data-icon-input>'
                . '<button type="button" class="btn btn-outline-secondary js-icon-pick" tabindex="-1"><i class="bi bi-grid-3x3-gap"></i></button>'
                . '</div>';
        }

        return '<input type="text" class="form-control form-control-sm" name="' . $inputName . '" placeholder="' . e($label) . '" value="' . e($value) . '">';
    };
@endphp

<div class="json-rows" data-json-rows data-name="{{ $name }}">
    <div class="json-rows-list">
        @foreach ($rows as $i => $row)
            <div class="json-row row g-2 align-items-start mb-2">
                @foreach ($fields as $field)
                    <div class="col">
                        {!! $renderRowField("{$name}[{$i}]", $field['key'], $field['label'], $field['type'] ?? 'text', $row[$field['key']] ?? '') !!}
                    </div>
                @endforeach
                <div class="col-auto">
                    <button type="button" class="btn btn-sm btn-outline-danger js-json-row-remove" title="Remove">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <button type="button" class="btn btn-sm btn-outline-primary js-json-row-add">
        <i class="bi bi-plus-lg"></i> {{ $addLabel }}
    </button>

    <template data-json-row-template>
        <div class="json-row row g-2 align-items-start mb-2">
            @foreach ($fields as $field)
                <div class="col">
                    {!! $renderRowField("{$name}[__INDEX__]", $field['key'], $field['label'], $field['type'] ?? 'text', '') !!}
                </div>
            @endforeach
            <div class="col-auto">
                <button type="button" class="btn btn-sm btn-outline-danger js-json-row-remove" title="Remove">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    </template>
</div>
