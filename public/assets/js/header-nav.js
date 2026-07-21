// Toggles .is-scrolled on the new consolidated header for a shadow/height
// transition. Deliberately independent of the old jQuery `.sticky-top`
// handler in main.js (that handler now finds no matching element, since the
// v2 redesign dropped the negative-margin/-top overlap trick it existed
// for — see NOTES.md/PROJECT_PROGRESS.md 2026-07-21 entries).
(function () {
    var header = document.querySelector('[data-header]');
    if (!header) {
        return;
    }

    function update() {
        header.classList.toggle('is-scrolled', window.scrollY > 24);
    }

    window.addEventListener('scroll', update, { passive: true });
    update();
})();
