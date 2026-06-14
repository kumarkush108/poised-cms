{{--
    Renders a single TemplateRegistry field as a form input.
    Expects: $namePrefix (e.g. "fields"), $fieldKey, $def, $currentValue (['value' => ..., 'media_id' => ...]), $images.
--}}

@php
    $inputName = "{$namePrefix}[{$fieldKey}]";
    $oldKey = "{$namePrefix}.{$fieldKey}";
    $type = $def['type'] ?? 'string';
@endphp

<div class="col-md-6 mb-3">
    <label class="form-label">
        {{ $def['label'] ?? $fieldKey }}
        @if ($def['required'] ?? false)
            <span class="text-danger">*</span>
        @endif
    </label>

    @switch($type)
        @case('text')
        @case('richtext')
            <textarea name="{{ $inputName }}" class="form-control" rows="4">{{ old($oldKey, $currentValue['value'] ?? null) }}</textarea>
            @break

        @case('integer')
            <input type="number" name="{{ $inputName }}" class="form-control"
                value="{{ old($oldKey, $currentValue['value'] ?? null) }}">
            @break

        @case('url')
            <input type="url" name="{{ $inputName }}" class="form-control"
                value="{{ old($oldKey, $currentValue['value'] ?? null) }}">
            @break

        @case('media')
            @include('admin.partials.media-select', [
                'name' => $inputName,
                'selected' => old($oldKey, $currentValue['media_id'] ?? null),
                'images' => $images,
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
