@php
    use App\Cms\Content;

    $faqHeading = Content::field($section, 'heading', 'Frequently Asked Questions');
    $faqSubheading = Content::field($section, 'subheading');
    $faqItems = Content::items($section, []);
@endphp

@if ($faqItems->isNotEmpty())
    <!-- FAQ Section Start -->
    <div class="container-fluid py-5">
        <div class="container">

            <div class="text-center mx-auto mb-5" style="max-width: 700px;">
                <h1 class="display-6 mb-3">{{ $faqHeading }}</h1>
                @if ($faqSubheading)
                    <p>{{ $faqSubheading }}</p>
                @endif
            </div>

            <div class="accordion" id="faqAccordion-{{ $section->id }}">

                @foreach ($faqItems as $index => $faq)
                    <div class="accordion-item {{ $loop->last ? '' : 'mb-3' }} border-0 shadow-sm">

                        <h2 class="accordion-header">
                            <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#faq-{{ $section->id }}-{{ $index }}">
                                {{ Content::itemField($faq, 'question') }}
                            </button>
                        </h2>

                        <div id="faq-{{ $section->id }}-{{ $index }}"
                            class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                            data-bs-parent="#faqAccordion-{{ $section->id }}">
                            <div class="accordion-body">
                                {!! Content::richtext(Content::itemField($faq, 'answer')) !!}
                            </div>
                        </div>

                    </div>
                @endforeach

            </div>

        </div>
    </div>
    <!-- FAQ Section End -->
@endif
