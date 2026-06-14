{{-- Reusable media dropdown. Expects: $name, $selected (media id or null), $images (collection of Media). --}}

<select name="{{ $name }}" class="form-select">
    <option value="">— None —</option>
    @foreach ($images as $media)
        <option value="{{ $media->id }}" @selected((string) $selected === (string) $media->id)>
            {{ $media->filename }}
        </option>
    @endforeach
</select>
