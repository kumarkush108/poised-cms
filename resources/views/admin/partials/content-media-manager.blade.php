{{--
    Reusable gallery/document manager for Product/BlogPost/NewsArticle edit
    forms. Expected variables:
    - $model: the Eloquent instance (already loaded with ->gallery/->documents)
    - $type: morph alias string ('product'|'blog_post'|'news_article')
    - $role: 'gallery' or 'document'
    - $items: the collection ($model->gallery or $model->documents)
    - $label: heading text, e.g. "Gallery Images" / "Downloads"
--}}

<div class="d-flex align-items-center justify-content-between mb-3">
    <h6 class="mb-0">{{ $label }}</h6>
    <button type="button" class="btn btn-sm btn-outline-primary js-gallery-add"
        data-type="{{ $type }}" data-id="{{ $model->id }}" data-role="{{ $role }}">
        <i class="bi bi-plus-lg"></i> {{ $role === 'gallery' ? 'Add Images' : 'Add Document' }}
    </button>
</div>

@if ($items->isEmpty())
    <p class="text-muted small mb-0">No {{ $role === 'gallery' ? 'images' : 'documents' }} yet.</p>
@elseif ($role === 'gallery')
    <div class="row g-3">
        @foreach ($items as $item)
            <div class="col-6 col-md-3">
                <div class="border rounded p-2 text-center h-100 d-flex flex-column">
                    <img src="{{ $item->media->url }}" alt="{{ $item->caption ?? $item->media->filename }}"
                        class="img-fluid rounded mb-2" style="aspect-ratio: 1/1; object-fit: cover;">
                    <div class="d-flex justify-content-center gap-1 mt-auto">
                        <form method="POST" action="{{ route('admin.content-media.move', $item) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="direction" value="up">
                            <button type="submit" class="btn btn-xs btn-outline-secondary" title="Move earlier">
                                <i class="bi bi-arrow-up"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.content-media.move', $item) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="direction" value="down">
                            <button type="submit" class="btn btn-xs btn-outline-secondary" title="Move later">
                                <i class="bi bi-arrow-down"></i>
                            </button>
                        </form>
                        <button type="button"
                            class="btn btn-xs btn-outline-danger js-confirm-delete"
                            data-confirm-title="Remove Image"
                            data-confirm-body="Remove this image from the gallery?"
                            data-confirm-action="{{ route('admin.content-media.destroy', $item) }}"
                            data-confirm-method="DELETE">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="list-group">
        @foreach ($items as $item)
            <div class="list-group-item d-flex align-items-center gap-3">
                <i class="bi bi-file-earmark-pdf fs-4 text-danger"></i>
                <a href="{{ $item->media->url }}" target="_blank" class="text-truncate" style="max-width: 200px;">
                    {{ $item->media->filename }}
                </a>
                <form method="POST" action="{{ route('admin.content-media.update', $item) }}" class="d-flex flex-grow-1 gap-2">
                    @csrf
                    @method('PATCH')
                    <input type="text" name="caption" class="form-control form-control-sm"
                        placeholder="Label (e.g. Brochure)" value="{{ $item->caption }}">
                    <button type="submit" class="btn btn-sm btn-outline-secondary">Save</button>
                </form>
                <button type="button"
                    class="btn btn-sm btn-outline-danger js-confirm-delete"
                    data-confirm-title="Remove Document"
                    data-confirm-body="Remove &ldquo;{{ addslashes($item->media->filename) }}&rdquo;?"
                    data-confirm-action="{{ route('admin.content-media.destroy', $item) }}"
                    data-confirm-method="DELETE">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        @endforeach
    </div>
@endif
