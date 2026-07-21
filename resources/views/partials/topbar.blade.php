<!-- Utility Strip Start -->
@php
    $utilitySettings = $themeSettings ?? collect();

    $utilitySocialLinks = [
        'fa-facebook-f' => \App\Cms\Content::settingValue($utilitySettings, 'facebook_url'),
        'fa-twitter' => \App\Cms\Content::settingValue($utilitySettings, 'twitter_url'),
        'fa-linkedin-in' => \App\Cms\Content::settingValue($utilitySettings, 'linkedin_url'),
        'fa-instagram' => \App\Cms\Content::settingValue($utilitySettings, 'instagram_url'),
        'fa-youtube' => \App\Cms\Content::settingValue($utilitySettings, 'youtube_url'),
    ];
@endphp
<div class="utility-strip d-none d-lg-block">
    <div class="container">
        <div class="utility-strip__row">

            @if (array_filter($utilitySocialLinks))
                <div class="utility-strip__social">
                    @foreach ($utilitySocialLinks as $icon => $url)
                        @if ($url)
                            <a href="{{ $url }}" target="_blank" rel="noopener"><i class="fab {{ $icon }}"></i></a>
                        @endif
                    @endforeach
                </div>
            @else
                <div></div>
            @endif

            <nav class="utility-strip__links">
                @if ($topbarMenu && $topbarMenu->items->isNotEmpty())
                    @foreach ($topbarMenu->items as $item)
                        @php
                            $href = $item->url ?? ($item->page ? $item->page->url() : '#');
                        @endphp
                        <a href="{{ $href }}" target="{{ $item->target }}">
                            @if ($item->icon)<i class="bi {{ $item->icon }} me-1"></i>@endif{{ $item->label }}
                        </a>
                    @endforeach
                @else
                    <a href="#">Career</a>
                    <a href="#">Support</a>
                    <a href="#">Terms</a>
                    <a href="#">FAQs</a>
                @endif
            </nav>

        </div>
    </div>
</div>
<!-- Utility Strip End -->
