{{--
    A reusable "icon" text input + live preview + picker-trigger button.
    Props: $name (input name), $value (current icon class, e.g. "bi-house" or "").
--}}
<div class="icon-field input-group">
    <span class="input-group-text icon-preview" data-icon-preview>
        <i class="bi {{ $value ?: 'bi-question-circle text-muted' }}"></i>
    </span>
    <input type="text" name="{{ $name }}" class="form-control" data-icon-input
        value="{{ $value }}" placeholder="e.g. bi-house">
    <button type="button" class="btn btn-outline-secondary js-icon-pick">
        <i class="bi bi-grid-3x3-gap"></i> Browse
    </button>
</div>
