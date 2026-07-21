{{-- Generic confirmation modal — replaces window.confirm() throughout the admin --}}
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-danger" id="confirmModalLabel">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    <span id="confirmModalTitle">Confirm</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body pt-2">
                <p id="confirmModalBody" class="mb-0">Are you sure?</p>
            </div>

            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmModalOk">Delete</button>
            </div>

        </div>
    </div>
</div>
