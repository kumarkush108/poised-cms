{{--
    Media field component.
    Props: $name (string), $selected (media id|null), $images (Media collection)
--}}
@php
    $currentMedia = $selected ? $images->firstWhere('id', (int) $selected) : null;
    $fieldId      = 'media-field-' . str_replace(['.', '[', ']'], '-', $name);
@endphp

<div class="media-field" data-field-id="{{ $fieldId }}">

    {{-- Hidden input carries the actual value --}}
    <input type="hidden"
           name="{{ $name }}"
           id="{{ $fieldId }}-input"
           value="{{ $selected ?? '' }}">

    {{-- Preview area --}}
    <div class="media-field-preview {{ $currentMedia ? '' : 'd-none' }}"
         id="{{ $fieldId }}-preview">
        @if ($currentMedia)
            <img src="{{ $currentMedia->url }}"
                 alt="{{ $currentMedia->alt_text ?? $currentMedia->filename }}"
                 class="media-field-thumb">
            <div class="media-field-meta">
                <span class="media-field-filename">{{ $currentMedia->filename }}</span>
                @if ($currentMedia->alt_text)
                    <span class="media-field-alt text-muted">{{ $currentMedia->alt_text }}</span>
                @endif
            </div>
        @endif
    </div>

    {{-- Empty state (shown when no image selected) --}}
    <div class="media-field-empty {{ $currentMedia ? 'd-none' : '' }}"
         id="{{ $fieldId }}-empty">
        <i class="bi bi-image text-muted"></i>
        <span class="text-muted small">No image selected</span>
    </div>

    {{-- Action buttons --}}
    <div class="media-field-actions mt-2 d-flex gap-2">
        <button type="button"
                class="btn btn-sm btn-outline-primary js-media-pick"
                data-field-id="{{ $fieldId }}"
                data-bs-toggle="modal"
                data-bs-target="#mediaPickerModal">
            <i class="bi bi-images me-1"></i>
            {{ $currentMedia ? 'Change Image' : 'Choose Image' }}
        </button>

        @if ($currentMedia)
            <button type="button"
                    class="btn btn-sm btn-outline-danger js-media-clear"
                    data-field-id="{{ $fieldId }}">
                <i class="bi bi-x-lg"></i>
            </button>
        @else
            <button type="button"
                    class="btn btn-sm btn-outline-danger js-media-clear d-none"
                    data-field-id="{{ $fieldId }}">
                <i class="bi bi-x-lg"></i>
            </button>
        @endif
    </div>

</div>
