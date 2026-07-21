@php
    use App\Cms\Content;

    $infoHeading = Content::field($section, 'heading', 'Contact Information');
    $infoAddress = Content::field($section, 'address');
    $infoPhone = Content::field($section, 'phone');
    $infoPhoneSecondary = Content::field($section, 'phone_secondary');
    $infoEmail = Content::field($section, 'email');
    $infoEmailSecondary = Content::field($section, 'email_secondary');
    $infoMapUrl = Content::field($section, 'map_embed_url');
@endphp

@if ($infoAddress || $infoPhone || $infoEmail || $infoMapUrl)
    <!-- Contact Info Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5 align-items-center">

                <div class="col-lg-5">
                    <h1 class="display-6 mb-4">{{ $infoHeading }}</h1>

                    @if ($infoAddress)
                        <div class="d-flex align-items-start mb-4">
                            <i class="bi bi-geo-alt text-primary fs-4 me-3"></i>
                            <span>{{ $infoAddress }}</span>
                        </div>
                    @endif

                    @if ($infoPhone)
                        <div class="d-flex align-items-start mb-4">
                            <i class="bi bi-telephone text-primary fs-4 me-3"></i>
                            <span>{{ $infoPhone }}@if ($infoPhoneSecondary), {{ $infoPhoneSecondary }}@endif</span>
                        </div>
                    @endif

                    @if ($infoEmail)
                        <div class="d-flex align-items-start mb-4">
                            <i class="bi bi-envelope text-primary fs-4 me-3"></i>
                            <span>{{ $infoEmail }}@if ($infoEmailSecondary), {{ $infoEmailSecondary }}@endif</span>
                        </div>
                    @endif
                </div>

                @if ($infoMapUrl)
                    <div class="col-lg-7">
                        <div class="ratio ratio-16x9 rounded overflow-hidden">
                            <iframe src="{{ $infoMapUrl }}" style="border:0;" allowfullscreen loading="lazy"></iframe>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
    <!-- Contact Info End -->
@endif
