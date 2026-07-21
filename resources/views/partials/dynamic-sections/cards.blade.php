@php
    use App\Cms\Content;

    $cardsHeading = Content::field($section, 'heading');
    $cardsSubheading = Content::field($section, 'subheading');
    $cardItems = Content::items($section, []);
@endphp

@if ($cardsHeading || $cardItems->isNotEmpty())
    <!-- Info Cards Start -->
    <div class="container-fluid py-5 bg-light">
        <div class="container">

            @if ($cardsHeading)
                <div class="text-center mx-auto mb-5" style="max-width: 700px;">
                    <h1 class="display-6 mb-3">{{ $cardsHeading }}</h1>
                    @if ($cardsSubheading)
                        <p>{{ $cardsSubheading }}</p>
                    @endif
                </div>
            @endif

            <div class="row g-4">
                @foreach ($cardItems as $card)
                    <div class="col-md-6 col-lg-4">
                        <div class="bg-white p-5 rounded shadow-sm h-100 text-center">
                            <div class="icon-box-primary mx-auto mb-4">
                                <i class="bi {{ Content::itemField($card, 'icon', 'bi-star') }} text-dark"></i>
                            </div>
                            <h3 class="mb-3">{{ Content::itemField($card, 'title') }}</h3>
                            <div class="mb-0">{!! Content::richtext(Content::itemField($card, 'description')) !!}</div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
    <!-- Info Cards End -->
@endif
