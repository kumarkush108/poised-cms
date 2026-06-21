@php
    use App\Cms\Content;

    $galleryHeading = Content::field($section, 'heading');
    $gallerySubheading = Content::field($section, 'subheading');
    $galleryItems = Content::items($section, [])
        ->filter(fn ($item) => Content::itemField($item, 'image') || Content::itemField($item, 'video_url'))
        ->values();

    $categories = $galleryItems
        ->map(fn ($item) => Content::itemField($item, 'category'))
        ->filter()
        ->unique()
        ->values();

    $initialVisible = 9;
@endphp

@if ($galleryHeading || $galleryItems->isNotEmpty())
    <!-- Media Gallery Start -->
    <div class="container-fluid py-5">
        <div class="container">

            @if ($galleryHeading)
                <div class="text-center mx-auto mb-4" style="max-width: 700px;">
                    <h1 class="display-6 mb-3">{{ $galleryHeading }}</h1>
                    @if ($gallerySubheading)
                        <p>{{ $gallerySubheading }}</p>
                    @endif
                </div>
            @endif

            @if ($categories->isNotEmpty())
                <div class="d-flex flex-wrap justify-content-center gap-2 mb-4 js-gallery-filters" data-gallery="gallery-{{ $section->id }}">
                    <button type="button" class="btn btn-sm btn-primary js-gallery-filter-btn active" data-filter="all">All</button>
                    @foreach ($categories as $category)
                        <button type="button" class="btn btn-sm btn-outline-primary js-gallery-filter-btn" data-filter="{{ $category }}">{{ $category }}</button>
                    @endforeach
                </div>
            @endif

            <div class="row g-4 js-gallery-grid" data-gallery="gallery-{{ $section->id }}">
                @foreach ($galleryItems as $index => $item)
                    @php
                        $image = Content::itemField($item, 'image');
                        $videoUrl = Content::itemField($item, 'video_url');
                        $category = Content::itemField($item, 'category', 'all');
                        $caption = Content::itemField($item, 'caption');
                        $thumbUrl = Content::mediaUrl($image, asset('assets/img/about-1.png'));
                    @endphp
                    <div class="col-lg-4 col-md-6 js-gallery-item {{ $index >= $initialVisible ? 'd-none' : '' }}"
                        data-category="{{ $category }}">
                        <div class="position-relative overflow-hidden rounded shadow-sm">
                            @if ($videoUrl)
                                <a href="#" class="js-gallery-video d-block" data-modal="galleryLightbox-{{ $section->id }}" data-video-url="{{ $videoUrl }}" data-caption="{{ $caption }}">
                                    <img src="{{ $thumbUrl }}" alt="{{ $caption ?? 'Video' }}" class="img-fluid w-100" style="aspect-ratio: 4/3; object-fit: cover;">
                                    <span class="position-absolute top-50 start-50 translate-middle text-white" style="font-size: 2.5rem;">
                                        <i class="bi bi-play-circle-fill"></i>
                                    </span>
                                </a>
                            @else
                                <a href="{{ $thumbUrl }}" class="js-gallery-image d-block" data-modal="galleryLightbox-{{ $section->id }}" data-caption="{{ $caption }}">
                                    <img src="{{ $thumbUrl }}" alt="{{ $caption ?? '' }}" class="img-fluid w-100" style="aspect-ratio: 4/3; object-fit: cover;">
                                </a>
                            @endif
                            @if ($caption)
                                <div class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-75 text-white small p-2">
                                    {{ $caption }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($galleryItems->count() > $initialVisible)
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-outline-primary js-gallery-load-more" data-gallery="gallery-{{ $section->id }}">
                        Load More
                    </button>
                </div>
            @endif

        </div>
    </div>
    <!-- Media Gallery End -->

    <!-- Lightbox Modal Start -->
    <div class="modal fade" id="galleryLightbox-{{ $section->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-dark">
                <div class="modal-header border-0">
                    <h6 class="modal-title text-white js-gallery-lightbox-caption"></h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center js-gallery-lightbox-body"></div>
            </div>
        </div>
    </div>
    <!-- Lightbox Modal End -->

    @once
        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.js-gallery-filters').forEach(function (filterBar) {
                    const galleryKey = filterBar.dataset.gallery;
                    const grid = document.querySelector('.js-gallery-grid[data-gallery="' + galleryKey + '"]');
                    if (! grid) return;

                    filterBar.querySelectorAll('.js-gallery-filter-btn').forEach(function (btn) {
                        btn.addEventListener('click', function () {
                            filterBar.querySelectorAll('.js-gallery-filter-btn').forEach(function (b) {
                                b.classList.remove('btn-primary', 'active');
                                b.classList.add('btn-outline-primary');
                            });
                            btn.classList.add('btn-primary', 'active');
                            btn.classList.remove('btn-outline-primary');

                            const filter = btn.dataset.filter;
                            grid.querySelectorAll('.js-gallery-item').forEach(function (item) {
                                const matches = filter === 'all' || item.dataset.category === filter;
                                item.classList.toggle('d-none', ! matches);
                            });
                        });
                    });
                });

                document.querySelectorAll('.js-gallery-load-more').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        const galleryKey = btn.dataset.gallery;
                        const grid = document.querySelector('.js-gallery-grid[data-gallery="' + galleryKey + '"]');
                        if (! grid) return;
                        grid.querySelectorAll('.js-gallery-item.d-none').forEach(function (item) {
                            item.classList.remove('d-none');
                        });
                        btn.classList.add('d-none');
                    });
                });

                document.querySelectorAll('.js-gallery-image').forEach(function (link) {
                    link.addEventListener('click', function (e) {
                        e.preventDefault();
                        const modal = document.getElementById(link.dataset.modal);
                        if (! modal) return;
                        modal.querySelector('.js-gallery-lightbox-body').innerHTML =
                            '<img src="' + link.getAttribute('href') + '" class="img-fluid">';
                        modal.querySelector('.js-gallery-lightbox-caption').textContent = link.dataset.caption || '';
                        bootstrap.Modal.getOrCreateInstance(modal).show();
                    });
                });

                document.querySelectorAll('.js-gallery-video').forEach(function (link) {
                    link.addEventListener('click', function (e) {
                        e.preventDefault();
                        const modal = document.getElementById(link.dataset.modal);
                        if (! modal) return;
                        modal.querySelector('.js-gallery-lightbox-body').innerHTML =
                            '<div class="ratio ratio-16x9"><iframe src="' + link.dataset.videoUrl + '" allowfullscreen></iframe></div>';
                        modal.querySelector('.js-gallery-lightbox-caption').textContent = link.dataset.caption || '';
                        bootstrap.Modal.getOrCreateInstance(modal).show();
                        modal.addEventListener('hidden.bs.modal', function () {
                            modal.querySelector('.js-gallery-lightbox-body').innerHTML = '';
                        }, { once: true });
                    });
                });
            });
        </script>
        @endpush
    @endonce
@endif
