@php
    use App\Cms\Content;

    $headerHeading = Content::field($section, 'heading', $page->title);
    $headerSubheading = Content::field($section, 'subheading');
    $headerBg = Content::mediaUrl(Content::field($section, 'background_image'), asset('assets/img/carousel-1.png'));
@endphp

<!-- Page Header Start -->
<div class="container-fluid page-header py-5 mb-5 wow fadeIn"
    style="background: linear-gradient(rgba(0,0,0,.75), rgba(0,0,0,.75)), url('{{ $headerBg }}') center center/cover no-repeat;"
    data-wow-delay="0.1s">

    <div class="container text-center py-5 mt-4">

        <h1 class="display-3 text-white mb-3 animated slideInDown">
            {{ $headerHeading }}
        </h1>

        @if ($headerSubheading)
            <p class="fs-5 text-white mb-4 animated slideInUp">{{ $headerSubheading }}</p>
        @endif

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item text-primary active">{{ $headerHeading }}</li>
            </ol>
        </nav>

    </div>
</div>
<!-- Page Header End -->
