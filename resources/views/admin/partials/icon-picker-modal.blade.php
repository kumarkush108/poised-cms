{{-- Icon Picker Modal — triggered by .js-icon-pick buttons next to any icon field --}}
<div class="modal fade" id="iconPickerModal" tabindex="-1" aria-labelledby="iconPickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="iconPickerModalLabel">
                    <i class="bi bi-grid-3x3-gap me-1"></i> Choose an Icon
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-3">

                <div class="d-flex gap-2 mb-3">
                    <input type="search"
                           id="iconPickerSearch"
                           class="form-control form-control-sm"
                           placeholder="Search icons, e.g. &quot;arrow&quot;, &quot;cart&quot;, &quot;check&quot;…"
                           autocomplete="off">
                    <button type="button" class="btn btn-sm btn-outline-secondary text-nowrap" id="iconPickerNone">
                        <i class="bi bi-x-circle me-1"></i> None
                    </button>
                </div>

                <div id="iconPickerGrid" class="icon-picker-grid">
                    <div class="icon-picker-empty text-center py-5 text-muted w-100">
                        <i class="bi bi-hourglass-split fs-2 d-block mb-2"></i>
                        Loading icons…
                    </div>
                </div>

            </div>

            <div class="modal-footer justify-content-between">
                <span id="iconPickerCount" class="text-muted small"></span>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>

        </div>
    </div>
</div>
