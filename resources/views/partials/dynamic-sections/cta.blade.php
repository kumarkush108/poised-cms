@php
    use App\Cms\Content;

    $ctaHeading = Content::field($section, 'heading');
    $ctaBody = Content::field($section, 'body');
    $ctaButtonText = Content::field($section, 'button_text');
    $ctaButtonUrl = Content::field($section, 'button_url', '#');
@endphp

@if ($ctaHeading)
    <!-- CTA Start -->
    <div class="container-fluid py-5 bg-dark text-white">
        <div class="container text-center">

            <h1 class="display-5 mb-4">{{ $ctaHeading }}</h1>

            @if ($ctaBody)
                <div class="fs-5 mb-4">{!! Content::richtext($ctaBody) !!}</div>
            @endif

            @if ($ctaButtonText)
                <a href="{{ $ctaButtonUrl }}" class="btn btn-primary py-3 px-5">{{ $ctaButtonText }}</a>
            @endif

        </div>
    </div>
    <!-- CTA End -->
@endif
