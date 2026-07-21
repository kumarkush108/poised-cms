@php
    use App\Cms\Content;

    $gridHeading = Content::field($section, 'heading');
    $gridSubheading = Content::field($section, 'subheading');
    $gridItems = Content::items($section, []);
@endphp

@if ($gridHeading || $gridItems->isNotEmpty())
    <!-- Services Grid Start -->
    <div class="container-fluid py-5 bg-light">
        <div class="container">

            @if ($gridHeading)
                <div class="text-center mx-auto mb-5" style="max-width: 700px;">
                    <h1 class="display-6 mb-3">{{ $gridHeading }}</h1>
                    @if ($gridSubheading)
                        <p>{{ $gridSubheading }}</p>
                    @endif
                </div>
            @endif

            <div class="row g-4">
                @foreach ($gridItems as $item)
                    @php $itemHighlights = Content::lines(Content::itemField($item, 'highlights')); @endphp
                    <div class="col-lg-4 col-md-6">
                        <div class="service-item h-100">

                            <div class="icon-box-primary mb-4">
                                <i class="bi {{ Content::itemField($item, 'icon', 'bi-gear') }} text-dark"></i>
                            </div>

                            <h4 class="mb-3">{{ Content::itemField($item, 'title') }}</h4>

                            <div class="mb-4">{!! Content::richtext(Content::itemField($item, 'description')) !!}</div>

                            @if (! empty($itemHighlights))
                                <ul class="list-unstyled small">
                                    @foreach ($itemHighlights as $highlight)
                                        <li class="{{ $loop->last ? '' : 'mb-2' }}">
                                            <i class="bi bi-check2 text-primary me-2"></i>{{ $highlight }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            @if ($linkText = Content::itemField($item, 'link_text'))
                                <a class="btn btn-light px-3 mt-2" href="{{ Content::itemField($item, 'link_url', '') }}">{{ $linkText }}<i
                                        class="bi bi-chevron-double-right ms-1"></i></a>
                            @endif

                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
    <!-- Services Grid End -->
@endif
