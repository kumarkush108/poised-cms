{{-- Media Picker Modal — triggered by .js-media-pick buttons --}}
<div class="modal fade" id="mediaPickerModal" tabindex="-1" aria-labelledby="mediaPickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="mediaPickerModalLabel">
                    <i class="bi bi-images me-1"></i> Choose Image
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-3">

                {{-- Search + upload row --}}
                <div class="d-flex gap-2 mb-3">
                    <input type="search"
                           id="mediaPickerSearch"
                           class="form-control form-control-sm"
                           placeholder="Search by filename or alt text…"
                           autocomplete="off">
                    <label class="btn btn-sm btn-outline-secondary text-nowrap mb-0">
                        <i class="bi bi-upload me-1"></i> Upload
                        <input type="file" id="mediaPickerUpload" accept="image/*" class="visually-hidden">
                    </label>
                </div>

                {{-- Upload progress --}}
                <div id="mediaPickerUploadProgress" class="d-none mb-3">
                    <div class="progress" style="height:4px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated w-100"></div>
                    </div>
                </div>

                {{-- Thumbnail grid --}}
                <div id="mediaPickerGrid" class="media-picker-grid">
                    <div class="media-picker-empty text-center py-5 text-muted w-100">
                        <i class="bi bi-hourglass-split fs-2 d-block mb-2"></i>
                        Loading…
                    </div>
                </div>

                {{-- Load more --}}
                <div class="text-center mt-3 d-none" id="mediaPickerLoadMore">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="mediaPickerLoadMoreBtn">
                        Load more
                    </button>
                </div>

            </div>

            <div class="modal-footer justify-content-between">
                <span id="mediaPickerCount" class="text-muted small"></span>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>

        </div>
    </div>
</div>
