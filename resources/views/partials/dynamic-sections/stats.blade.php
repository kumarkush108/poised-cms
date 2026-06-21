@php
    use App\Cms\Content;

    $statsHeading = Content::field($section, 'heading');
    $statsSubheading = Content::field($section, 'subheading');
    $statsItems = Content::items($section, []);
@endphp

@if ($statsItems->isNotEmpty())
    <!-- Stats Start -->
    <div class="container-fluid py-5 bg-primary">
        <div class="container">

            @if ($statsHeading)
                <div class="text-center text-white mx-auto mb-4" style="max-width: 600px;">
                    <h2 class="text-white mb-2">{{ $statsHeading }}</h2>
                    @if ($statsSubheading)
                        <p class="text-white-50 mb-0">{{ $statsSubheading }}</p>
                    @endif
                </div>
            @endif

            <div class="row text-center text-white g-4">
                @foreach ($statsItems as $stat)
                    <div class="col-lg-3 col-md-6">
                        <h1 class="display-4 text-white">{{ Content::itemField($stat, 'value') }}</h1>
                        <p class="mb-0">{{ Content::itemField($stat, 'label') }}</p>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
    <!-- Stats End -->
@endif
