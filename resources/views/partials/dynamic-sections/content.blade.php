@php
    use App\Cms\Content;

    $contentHeading = Content::field($section, 'heading');
    $contentBody = Content::field($section, 'body');
@endphp

@if ($contentHeading || $contentBody)
    <!-- Content Block Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    @if ($contentHeading)
                        <h1 class="display-6 mb-4 text-center">{{ $contentHeading }}</h1>
                    @endif
                    @if ($contentBody)
                        <div>{!! Content::richtext($contentBody) !!}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Content Block End -->
@endif
