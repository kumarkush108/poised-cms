/**
 * Admin Editor — Phase K.1
 * Handles: Tiptap richtext, media picker modal, confirm-delete modal, icon preview
 */

import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';
import Link from '@tiptap/extension-link';
import Image from '@tiptap/extension-image';

// ── CSRF helper ──────────────────────────────────────────────────────────────

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content ?? '';
}

// ── Richtext editors (Tiptap) ────────────────────────────────────────────────

// WeakSet tracks which [data-richtext] divs have been mounted so we never
// double-initialise the same element.
const mountedEditors = new WeakSet();

function mountEditor(el) {
    if (mountedEditors.has(el)) return;
    mountedEditors.add(el);

    // Find textarea and toolbar by DOM traversal rather than a CSS attribute
    // selector. Field names like "fields[body]" contain square brackets that
    // require CSS-escaping, and some browser/selector combinations silently
    // return null — which would cause mountEditor to bail out and leave a
    // non-editable static div.
    const wrapper  = el.closest('.richtext-wrapper');
    const textarea = wrapper?.querySelector('textarea');
    const toolbar  = wrapper?.querySelector('.richtext-toolbar');

    if (!textarea) return;

    // Read initial content from the hidden textarea, not from el.innerHTML.
    // The textarea holds the server-rendered DB value; the browser automatically
    // unescapes HTML entities (e.g. &lt;p&gt; → <p>), giving Tiptap valid HTML
    // even when the value was emitted through Blade's {{ }} escaping.
    const editor = new Editor({
        element: el,
        extensions: [
            StarterKit.configure({
                heading: { levels: [2, 3] },
            }),
            Link.configure({ openOnClick: false }),
            Image.configure({ inline: false }),
        ],
        content: textarea.value || '',
        onUpdate({ editor }) {
            textarea.value = editor.getHTML();
        },
    });

    if (toolbar) buildToolbar(toolbar, editor);
}

function initRichtextEditors() {
    // Init editors that are already visible on page load (not inside a collapsed
    // Bootstrap panel). Collapsed panels have display:none before expansion.
    document.querySelectorAll('[data-richtext]').forEach((el) => {
        if (!el.closest('.collapse:not(.show)')) {
            mountEditor(el);
        }
    });

    // Lazily init editors when their Bootstrap collapse fully opens.
    // We use 'shown.bs.collapse' (fires AFTER the animation) rather than
    // 'show.bs.collapse' (fires BEFORE Bootstrap removes display:none), because
    // ProseMirror needs the element to have real dimensions to render correctly.
    //
    // We also skip editors that are still inside a deeper, still-collapsed nested
    // panel (e.g. an item-body inside the section-body that just opened) — those
    // will be handled by their own 'shown.bs.collapse' event.
    document.addEventListener('shown.bs.collapse', (e) => {
        e.target.querySelectorAll('[data-richtext]').forEach((el) => {
            if (!el.closest('.collapse:not(.show)')) {
                mountEditor(el);
            }
        });
    });
}

function buildToolbar(toolbar, editor) {
    const buttons = [
        { label: '<b>B</b>',  title: 'Bold',          cmd: () => editor.chain().focus().toggleBold().run(),          active: () => editor.isActive('bold') },
        { label: '<i>I</i>',  title: 'Italic',        cmd: () => editor.chain().focus().toggleItalic().run(),        active: () => editor.isActive('italic') },
        { label: 'H2',        title: 'Heading 2',     cmd: () => editor.chain().focus().toggleHeading({ level: 2 }).run(), active: () => editor.isActive('heading', { level: 2 }) },
        { label: 'H3',        title: 'Heading 3',     cmd: () => editor.chain().focus().toggleHeading({ level: 3 }).run(), active: () => editor.isActive('heading', { level: 3 }) },
        null, // separator
        { label: '&bull;&bull;', title: 'Bullet list', cmd: () => editor.chain().focus().toggleBulletList().run(),   active: () => editor.isActive('bulletList') },
        { label: '1.',        title: 'Ordered list',  cmd: () => editor.chain().focus().toggleOrderedList().run(),   active: () => editor.isActive('orderedList') },
        null, // separator
        { label: '&#8220;&#8221;', title: 'Blockquote', cmd: () => editor.chain().focus().toggleBlockquote().run(), active: () => editor.isActive('blockquote') },
        { label: '&mdash;',  title: 'Horizontal rule', cmd: () => editor.chain().focus().setHorizontalRule().run(),  active: () => false },
        null, // separator
        { label: '<i class="bi bi-image"></i>', title: 'Insert Image', cmd: () => openMediaPicker({
            onSelect: (item) => editor.chain().focus().setImage({ src: item.url, alt: item.alt || item.filename }).run(),
        }), active: () => false },
        null, // separator
        { label: '&#8617;',  title: 'Undo',           cmd: () => editor.chain().focus().undo().run(),               active: () => false },
        { label: '&#8618;',  title: 'Redo',           cmd: () => editor.chain().focus().redo().run(),               active: () => false },
    ];

    const btnEls = [];

    buttons.forEach((def) => {
        if (def === null) {
            const sep = document.createElement('span');
            sep.className = 'toolbar-sep';
            toolbar.appendChild(sep);
            return;
        }
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.innerHTML = def.label;
        btn.title = def.title;
        btn.addEventListener('click', (e) => { e.preventDefault(); def.cmd(); updateActive(); });
        toolbar.appendChild(btn);
        btnEls.push({ el: btn, active: def.active });
    });

    function updateActive() {
        btnEls.forEach(({ el, active }) => el.classList.toggle('is-active', active()));
    }

    editor.on('selectionUpdate', updateActive);
    editor.on('transaction',     updateActive);
}

// ── Media picker modal ────────────────────────────────────────────────────────

let activeFieldId = null;

// Picking a media item normally writes straight into a "field" (the
// .js-media-pick/.js-media-clear flow below). Other callers — the Tiptap
// "Insert Image" button, and the gallery/document manager for
// Product/BlogPost/NewsArticle — instead want a callback invoked per
// selection, optionally without auto-closing the modal (multi-select).
// `_openMediaPickerImpl` is assigned once initMediaPicker() runs.
let pickerCallback = null;
let pickerMulti = false;
let _openMediaPickerImpl = null;

export function openMediaPicker(options = {}) {
    _openMediaPickerImpl?.(options);
}

function initMediaPicker() {
    const modal      = document.getElementById('mediaPickerModal');
    const grid       = document.getElementById('mediaPickerGrid');
    const searchInput = document.getElementById('mediaPickerSearch');
    const countEl    = document.getElementById('mediaPickerCount');
    const loadMoreWrap = document.getElementById('mediaPickerLoadMore');
    const loadMoreBtn  = document.getElementById('mediaPickerLoadMoreBtn');
    const uploadInput  = document.getElementById('mediaPickerUpload');
    const uploadProgress = document.getElementById('mediaPickerUploadProgress');

    if (!modal) return;

    let currentPage = 1;
    let currentSearch = '';
    let hasMore = false;
    let loading = false;

    const endpoint = document.head.querySelector('meta[name="media-modal-items-url"]')?.content
        ?? (window.location.origin + '/admin/media/modal-items');

    // Open modal: record which field triggered it
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.js-media-pick');
        if (!btn) return;
        activeFieldId = btn.dataset.fieldId;
        pickerCallback = null;
        currentPage = 1;
        currentSearch = '';
        if (searchInput) searchInput.value = '';
        loadItems(true);
    });

    // Programmatic API for callers that aren't a plain "set this field" button
    // (Tiptap's "Insert Image", and the multi-select gallery/document manager).
    _openMediaPickerImpl = ({ onSelect, multi = false } = {}) => {
        activeFieldId = null;
        pickerCallback = onSelect ?? null;
        pickerMulti = multi;
        currentPage = 1;
        currentSearch = '';
        if (searchInput) searchInput.value = '';
        loadItems(true);
        bootstrap.Modal.getOrCreateInstance(modal).show();
    };

    // Search
    if (searchInput) {
        let searchTimer;
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                currentSearch = searchInput.value.trim();
                currentPage = 1;
                loadItems(true);
            }, 350);
        });
    }

    // Load more
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', () => {
            if (!loading && hasMore) {
                currentPage++;
                loadItems(false);
            }
        });
    }

    // Upload from within modal
    if (uploadInput) {
        uploadInput.addEventListener('change', async () => {
            const file = uploadInput.files[0];
            if (!file) return;

            const fd = new FormData();
            fd.append('file', file);
            fd.append('_token', csrfToken());

            uploadProgress?.classList.remove('d-none');
            uploadInput.value = '';

            try {
                const res = await fetch('/admin/media', {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                    body: fd,
                });
                if (!res.ok) throw new Error('Upload failed');
                currentPage = 1;
                currentSearch = '';
                if (searchInput) searchInput.value = '';
                loadItems(true);
            } catch (err) {
                alert('Upload failed: ' + err.message);
            } finally {
                uploadProgress?.classList.add('d-none');
            }
        });
    }

    async function loadItems(reset) {
        if (loading) return;
        loading = true;

        if (reset && grid) grid.innerHTML = '<div class="media-picker-empty text-center py-5 w-100"><i class="bi bi-hourglass-split fs-2 d-block mb-2 text-muted"></i>Loading…</div>';

        const params = new URLSearchParams({ page: currentPage });
        if (currentSearch) params.set('search', currentSearch);

        try {
            const res  = await fetch(`/admin/media/modal-items?${params}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            });
            const data = await res.json();

            if (reset && grid) grid.innerHTML = '';

            hasMore = data.has_more;
            if (countEl) countEl.textContent = `${data.total} file${data.total !== 1 ? 's' : ''}`;
            if (loadMoreWrap) loadMoreWrap.classList.toggle('d-none', !hasMore);

            if (data.items.length === 0 && reset) {
                grid?.insertAdjacentHTML('beforeend',
                    '<div class="media-picker-empty text-center py-5 w-100 text-muted"><i class="bi bi-image fs-2 d-block mb-2"></i>No media found.</div>');
            }

            data.items.forEach((item) => {
                const el = document.createElement('div');
                el.className = 'media-picker-item';
                el.dataset.id       = item.id;
                el.dataset.url      = item.url;
                el.dataset.filename = item.filename;
                el.dataset.alt      = item.alt;

                if (item.mime.startsWith('image/')) {
                    el.innerHTML = `<img src="${item.url}" alt="${item.alt || item.filename}" loading="lazy">
                                    <span class="media-picker-name">${item.filename}</span>`;
                } else {
                    el.innerHTML = `<div class="d-flex flex-column align-items-center justify-content-center h-100 p-2 text-muted">
                                      <i class="bi bi-file-earmark fs-3"></i>
                                      <span class="media-picker-name">${item.filename}</span>
                                    </div>`;
                }

                el.addEventListener('click', () => selectItem(item));
                grid?.appendChild(el);
            });

        } catch (err) {
            if (grid) grid.innerHTML = `<div class="media-picker-empty text-center py-5 w-100 text-danger">Failed to load media.</div>`;
        } finally {
            loading = false;
        }
    }

    function selectItem(item) {
        if (pickerCallback) {
            pickerCallback(item);
            if (!pickerMulti) closeModal();
            return;
        }

        if (!activeFieldId) return;

        const hiddenInput = document.getElementById(`${activeFieldId}-input`);
        const preview     = document.getElementById(`${activeFieldId}-preview`);
        const empty       = document.getElementById(`${activeFieldId}-empty`);
        const pickBtn     = document.querySelector(`[data-field-id="${activeFieldId}"].js-media-pick`);
        const clearBtn    = document.querySelector(`[data-field-id="${activeFieldId}"].js-media-clear`);

        if (hiddenInput) hiddenInput.value = item.id;

        if (preview) {
            preview.innerHTML = `
                <img src="${item.url}" alt="${item.alt || item.filename}" class="media-field-thumb">
                <div class="media-field-meta">
                    <span class="media-field-filename">${item.filename}</span>
                    ${item.alt ? `<span class="media-field-alt text-muted">${item.alt}</span>` : ''}
                </div>`;
            preview.classList.remove('d-none');
        }

        if (empty) empty.classList.add('d-none');
        if (pickBtn) pickBtn.textContent = 'Change Image';
        if (clearBtn) clearBtn.classList.remove('d-none');

        closeModal();
    }

    function closeModal() {
        const bsModal = bootstrap.Modal.getInstance(document.getElementById('mediaPickerModal'));
        bsModal?.hide();
    }
}

// ── Gallery / document manager (Product, BlogPost, NewsArticle) ───────────────

// "Add Images"/"Add Documents" buttons open the media picker in multi-select
// mode; each pick is posted immediately (so a slow connection or closing the
// modal partway through never loses earlier picks), and the page reloads once
// the modal closes if anything was actually added — simplest way to reflect
// the new row(s) using the same server-rendered list/move/remove markup the
// rest of the admin already uses, with no client-side list-patching to keep
// in sync.
function initContentMediaManager() {
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.js-gallery-add');
        if (!btn) return;

        const { type, id, role } = btn.dataset;
        let addedAny = false;

        openMediaPicker({
            multi: true,
            onSelect: async (item) => {
                try {
                    const res = await fetch(`/admin/content-media/${type}/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken(),
                        },
                        body: JSON.stringify({ media_id: item.id, role }),
                    });
                    if (res.ok) addedAny = true;
                } catch (_) { /* surfaced as "nothing changed" on reload */ }
            },
        });

        document.getElementById('mediaPickerModal')?.addEventListener('hidden.bs.modal', () => {
            if (addedAny) window.location.reload();
        }, { once: true });
    });
}

// ── Clear media selection ─────────────────────────────────────────────────────

function initMediaClear() {
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.js-media-clear');
        if (!btn) return;

        const fieldId    = btn.dataset.fieldId;
        const hiddenInput = document.getElementById(`${fieldId}-input`);
        const preview    = document.getElementById(`${fieldId}-preview`);
        const empty      = document.getElementById(`${fieldId}-empty`);
        const pickBtn    = document.querySelector(`[data-field-id="${fieldId}"].js-media-pick`);

        if (hiddenInput) hiddenInput.value = '';
        if (preview)     preview.classList.add('d-none');
        if (empty)       empty.classList.remove('d-none');
        if (pickBtn)     pickBtn.innerHTML = '<i class="bi bi-images me-1"></i> Choose Image';
        btn.classList.add('d-none');
    });
}

// ── Confirm-delete modal ──────────────────────────────────────────────────────

function initConfirmModal() {
    const modal   = document.getElementById('confirmModal');
    const titleEl = document.getElementById('confirmModalTitle');
    const bodyEl  = document.getElementById('confirmModalBody');
    const okBtn   = document.getElementById('confirmModalOk');

    if (!modal || !okBtn) return;

    let pendingAction = null;
    let pendingMethod = 'DELETE';

    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.js-confirm-delete');
        if (!btn) return;

        e.preventDefault();

        pendingAction = btn.dataset.confirmAction;
        pendingMethod = btn.dataset.confirmMethod ?? 'DELETE';

        if (titleEl) titleEl.textContent = btn.dataset.confirmTitle ?? 'Confirm';
        if (bodyEl)  bodyEl.innerHTML    = btn.dataset.confirmBody  ?? 'Are you sure?';

        const bs = new bootstrap.Modal(modal);
        bs.show();
    });

    okBtn.addEventListener('click', () => {
        if (!pendingAction) return;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = pendingAction;
        form.style.display = 'none';

        const csrf = document.createElement('input');
        csrf.type  = 'hidden';
        csrf.name  = '_token';
        csrf.value = csrfToken();
        form.appendChild(csrf);

        if (pendingMethod !== 'POST') {
            const methodField = document.createElement('input');
            methodField.type  = 'hidden';
            methodField.name  = '_method';
            methodField.value = pendingMethod;
            form.appendChild(methodField);
        }

        document.body.appendChild(form);

        const bsModal = bootstrap.Modal.getInstance(modal);
        bsModal?.hide();

        form.submit();
    });
}

// ── Icon field live preview ───────────────────────────────────────────────────

function initIconPreview() {
    document.addEventListener('input', (e) => {
        const input = e.target.closest('[data-icon-input]');
        if (!input) return;

        const wrapper = input.closest('.icon-field');
        const preview = wrapper?.querySelector('[data-icon-preview] i');
        if (!preview) return;

        const val = input.value.trim();
        preview.className = val ? `bi ${val}` : 'bi bi-question-circle text-muted';
    });
}

// ── Icon picker modal ─────────────────────────────────────────────────────────

// The full Bootstrap Icons name list (no "bi-" prefix) is generated once from
// the vendored bootstrap-icons.css — see public/admin/assets/data/bootstrap-icons.json.
// Fetched lazily on first open and cached here; ~2000 names is small enough to
// render as plain DOM nodes once and filter by toggling display, rather than
// re-rendering on every keystroke.
let iconNamesCache = null;
let activeIconInput = null;

function initIconPicker() {
    const modal      = document.getElementById('iconPickerModal');
    const grid       = document.getElementById('iconPickerGrid');
    const searchInput = document.getElementById('iconPickerSearch');
    const noneBtn    = document.getElementById('iconPickerNone');
    const countEl    = document.getElementById('iconPickerCount');

    if (!modal) return;

    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.js-icon-pick');
        if (!btn) return;

        activeIconInput = btn.closest('.icon-field')?.querySelector('[data-icon-input]') ?? null;
        if (!activeIconInput) return;

        if (searchInput) searchInput.value = '';
        ensureGridRendered().then(() => {
            filterGrid('');
            highlightCurrent();
        });

        bootstrap.Modal.getOrCreateInstance(modal).show();
    });

    if (searchInput) {
        let searchTimer;
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => filterGrid(searchInput.value.trim().toLowerCase()), 150);
        });
    }

    if (noneBtn) {
        noneBtn.addEventListener('click', () => {
            setActiveIconValue('');
            closeModal();
        });
    }

    async function ensureGridRendered() {
        if (iconNamesCache !== null) return;

        if (grid) grid.innerHTML = '<div class="icon-picker-empty text-center py-5 w-100"><i class="bi bi-hourglass-split fs-2 d-block mb-2 text-muted"></i>Loading icons…</div>';

        try {
            const res = await fetch('/admin/assets/data/bootstrap-icons.json');
            iconNamesCache = await res.json();
        } catch (err) {
            // Leave the cache as null so the next open retries the fetch
            // instead of being stuck showing this error permanently.
            if (grid) grid.innerHTML = '<div class="icon-picker-empty text-center py-5 w-100 text-danger">Failed to load icons.</div>';
            return;
        }

        if (!grid) return;
        grid.innerHTML = '';

        iconNamesCache.forEach((name) => {
            const el = document.createElement('div');
            el.className = 'icon-picker-item';
            el.dataset.name = name;
            el.title = name;
            el.innerHTML = `<i class="bi bi-${name}"></i>`;
            el.addEventListener('click', () => {
                setActiveIconValue(`bi-${name}`);
                closeModal();
            });
            grid.appendChild(el);
        });
    }

    function filterGrid(term) {
        if (!grid) return;
        let visible = 0;

        grid.querySelectorAll('.icon-picker-item').forEach((el) => {
            const matches = !term || el.dataset.name.includes(term);
            el.classList.toggle('d-none', !matches);
            if (matches) visible++;
        });

        if (countEl) countEl.textContent = `${visible} icon${visible !== 1 ? 's' : ''}`;
    }

    function highlightCurrent() {
        if (!grid) return;
        const current = (activeIconInput?.value ?? '').trim().replace(/^bi-/, '');
        grid.querySelectorAll('.icon-picker-item').forEach((el) => {
            el.classList.toggle('selected', !!current && el.dataset.name === current);
        });
    }

    function setActiveIconValue(value) {
        if (!activeIconInput) return;
        activeIconInput.value = value;
        activeIconInput.dispatchEvent(new Event('input', { bubbles: true }));
    }

    function closeModal() {
        bootstrap.Modal.getInstance(modal)?.hide();
    }
}

// ── Repeatable JSON-column rows (Product features/specifications) ─────────────

// Pure client-side add/remove; the whole array is submitted with the parent
// form (no separate endpoint). Removing a row can leave a gap in the index
// sequence — the receiving controller re-indexes with array_values() before
// JSON-encoding, so gaps here are harmless.
function initJsonRows() {
    document.addEventListener('click', (e) => {
        const addBtn = e.target.closest('.js-json-row-add');
        if (addBtn) {
            const wrapper = addBtn.closest('[data-json-rows]');
            const list = wrapper?.querySelector('.json-rows-list');
            const template = wrapper?.querySelector('template[data-json-row-template]');
            if (!list || !template) return;

            const index = list.children.length;
            const html = template.innerHTML.replaceAll('__INDEX__', String(index));
            list.insertAdjacentHTML('beforeend', html);
            return;
        }

        const removeBtn = e.target.closest('.js-json-row-remove');
        if (removeBtn) {
            removeBtn.closest('.json-row')?.remove();
        }
    });
}

// ── Accordion state persistence (sessionStorage) ──────────────────────────────

function initAccordionState() {
    const KEY = 'cms-accordion-' + (location.pathname);

    // Restore
    try {
        const saved = JSON.parse(sessionStorage.getItem(KEY) ?? '[]');
        saved.forEach((id) => {
            const el = document.getElementById(id);
            if (el) {
                el.classList.add('show');
                const toggle = document.querySelector(`[data-bs-target="#${id}"]`);
                if (toggle) toggle.setAttribute('aria-expanded', 'true');
                const chevron = toggle?.querySelector('.accordion-chevron');
                if (chevron) chevron.style.transform = 'rotate(90deg)';

                // Bootstrap collapse events don't fire when we manually add
                // class="show", so mount richtext editors that are now visible.
                // Skip any that are still inside a deeper, still-closed item panel.
                el.querySelectorAll('[data-richtext]').forEach((rte) => {
                    if (!rte.closest('.collapse:not(.show)')) mountEditor(rte);
                });
            }
        });
    } catch (_) { /* ignore corrupt storage */ }

    // Persist on toggle
    document.addEventListener('shown.bs.collapse', (e) => saveState(e.target.id, true));
    document.addEventListener('hidden.bs.collapse', (e) => saveState(e.target.id, false));

    function saveState(id, isOpen) {
        if (!id.startsWith('section-body-') && !id.startsWith('item-body-')) return;
        try {
            const saved = new Set(JSON.parse(sessionStorage.getItem(KEY) ?? '[]'));
            isOpen ? saved.add(id) : saved.delete(id);
            sessionStorage.setItem(KEY, JSON.stringify([...saved]));
        } catch (_) { /* ignore */ }
    }
}

// ── Boot ──────────────────────────────────────────────────────────────────────

document.addEventListener('DOMContentLoaded', () => {
    initRichtextEditors();
    initMediaPicker();
    initMediaClear();
    initContentMediaManager();
    initJsonRows();
    initConfirmModal();
    initIconPreview();
    initIconPicker();
    initAccordionState();
});
