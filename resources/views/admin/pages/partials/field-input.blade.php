{{--
    Renders a single TemplateRegistry field as a form input.
    Expects: $namePrefix (e.g. "fields"), $fieldKey, $def, $currentValue (['value' => ..., 'media_id' => ...]), $images.
--}}

@php
    $inputName = "{$namePrefix}[{$fieldKey}]";
    $oldKey    = "{$namePrefix}.{$fieldKey}";
    $type      = $def['type'] ?? 'string';

    $colClass = match($type) {
        'richtext' => 'col-12',
        'text'     => 'col-12',
        'integer'  => 'col-md-4',
        'media'    => 'col-md-6',
        default    => 'col-md-6',
    };
@endphp

<div class="{{ $colClass }} mb-3">
    <label class="form-label">
        {{ $def['label'] ?? $fieldKey }}
        @if ($def['required'] ?? false)
            <span class="text-danger">*</span>
        @endif
    </label>

    @switch($type)
        @case('richtext')
            <div class="richtext-wrapper">
                <div class="richtext-toolbar" data-toolbar-for="{{ $inputName }}"></div>
                <div class="richtext-editor" data-richtext data-name="{{ $inputName }}">
                    {!! old($oldKey, $currentValue['value'] ?? '') !!}
                </div>
                <textarea name="{{ $inputName }}" class="richtext-input visually-hidden" rows="8">{{ old($oldKey, $currentValue['value'] ?? null) }}</textarea>
            </div>
            @break

        @case('text')
            @if ($fieldKey === 'highlights')
                {{-- Newline-delimited list — must stay as plain textarea so the frontend can split on \n --}}
                <textarea name="{{ $inputName }}" class="form-control" rows="5"
                          placeholder="One highlight per line">{{ old($oldKey, $currentValue['value'] ?? null) }}</textarea>
            @else
                <div class="richtext-wrapper">
                    <div class="richtext-toolbar" data-toolbar-for="{{ $inputName }}"></div>
                    <div class="richtext-editor" data-richtext data-name="{{ $inputName }}">
                        {!! old($oldKey, $currentValue['value'] ?? '') !!}
                    </div>
                    <textarea name="{{ $inputName }}" class="richtext-input visually-hidden">{{ old($oldKey, $currentValue['value'] ?? null) }}</textarea>
                </div>
            @endif
            @break

        @case('integer')
            <input type="number" name="{{ $inputName }}" class="form-control"
                value="{{ old($oldKey, $currentValue['value'] ?? null) }}">
            @break

        @case('url')
            <input type="url" name="{{ $inputName }}" class="form-control"
                value="{{ old($oldKey, $currentValue['value'] ?? null) }}">
            @break

        @case('icon')
            @php $iconValue = old($oldKey, $currentValue['value'] ?? ''); @endphp
            <div class="input-group">
                <span class="input-group-text icon-preview" data-icon-preview>
                    @if ($iconValue)
                        <i class="bi {{ $iconValue }}"></i>
                    @else
                        <i class="bi bi-question-circle text-muted"></i>
                    @endif
                </span>
                <input type="text" name="{{ $inputName }}" class="form-control" data-icon-input
                    value="{{ $iconValue }}"
                    placeholder="e.g. bi-ev-station">
            </div>
            @break

        @case('media')
            @include('admin.partials.media-select', [
                'name'     => $inputName,
                'selected' => old($oldKey, $currentValue['media_id'] ?? null),
                'images'   => $images,
            ])
            @break

        @default
            <input type="text" name="{{ $inputName }}" class="form-control"
                value="{{ old($oldKey, $currentValue['value'] ?? null) }}">
    @endswitch

    @error($oldKey)
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>
