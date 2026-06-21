{{--
    Repeatable rows editor backed by a JSON array column (e.g. Product
    features/specifications) — no separate table/controller, submitted as
    part of the parent form. Expected variables:
    - $name: form field base name, e.g. 'features' or 'specifications'
    - $rows: current array of associative arrays
    - $fields: ordered [['key'=>,'label'=>,'type'=>'text'|'textarea'], ...]
    - $addLabel: button text, e.g. "Add Feature"
--}}

<div class="json-rows" data-json-rows data-name="{{ $name }}">
    <div class="json-rows-list">
        @foreach ($rows as $i => $row)
            <div class="json-row row g-2 align-items-start mb-2">
                @foreach ($fields as $field)
                    <div class="col">
                        @if (($field['type'] ?? 'text') === 'textarea')
                            <textarea class="form-control form-control-sm" name="{{ $name }}[{{ $i }}][{{ $field['key'] }}]" placeholder="{{ $field['label'] }}" rows="2">{{ $row[$field['key']] ?? '' }}</textarea>
                        @else
                            <input type="text" class="form-control form-control-sm" name="{{ $name }}[{{ $i }}][{{ $field['key'] }}]" placeholder="{{ $field['label'] }}" value="{{ $row[$field['key']] ?? '' }}">
                        @endif
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
                    @if (($field['type'] ?? 'text') === 'textarea')
                        <textarea class="form-control form-control-sm" name="{{ $name }}[__INDEX__][{{ $field['key'] }}]" placeholder="{{ $field['label'] }}" rows="2"></textarea>
                    @else
                        <input type="text" class="form-control form-control-sm" name="{{ $name }}[__INDEX__][{{ $field['key'] }}]" placeholder="{{ $field['label'] }}">
                    @endif
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
