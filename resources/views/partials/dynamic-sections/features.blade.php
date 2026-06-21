@php
    use App\Cms\Content;

    $featuresHeading = Content::field($section, 'heading');
    $featuresSubheading = Content::field($section, 'subheading');
    $featureItems = Content::items($section, []);
@endphp

@if ($featuresHeading || $featureItems->isNotEmpty())
    <!-- Features Start -->
    <div class="container-fluid py-5">
        <div class="container">

            @if ($featuresHeading)
                <div class="text-center mx-auto mb-5" style="max-width: 700px;">
                    <h1 class="display-6 mb-3">{{ $featuresHeading }}</h1>
                    @if ($featuresSubheading)
                        <p>{{ $featuresSubheading }}</p>
                    @endif
                </div>
            @endif

            <div class="row g-4">
                @foreach ($featureItems as $feature)
                    <div class="col-lg-3 col-md-6">
                        <div class="feature-item border rounded p-4 h-100 text-center">
                            <div class="icon-box-primary mx-auto mb-4">
                                <i class="bi {{ Content::itemField($feature, 'icon', 'bi-lightbulb') }} text-dark"></i>
                            </div>
                            <h5>{{ Content::itemField($feature, 'title') }}</h5>
                            <div class="mb-0">{!! Content::richtext(Content::itemField($feature, 'description')) !!}</div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
    <!-- Features End -->
@endif
