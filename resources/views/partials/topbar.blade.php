<!-- Topbar Start -->
@php
    $topbarAddress = \App\Cms\Content::settingValue($themeSettings ?? collect(), 'address', 'F-15, First Floor, Block D 242, Sector 63, Noida-201301');
    $topbarHours = \App\Cms\Content::settingValue($themeSettings ?? collect(), 'business_hours', 'Mon-Sat 09am-5pm, Sun Closed');
@endphp
<div class="container-fluid py-2 d-none d-lg-flex">
    <div class="container">
        <div class="d-flex justify-content-between">

            <!-- Left Info -->
            <div>
                <small class="me-3">
                    <i class="fa fa-map-marker-alt me-2"></i>
                    {{ $topbarAddress }}
                </small>

                <small class="me-3">
                    <i class="fa fa-clock me-2"></i>
                    {{ $topbarHours }}
                </small>
            </div>

            <!-- Right Links -->
            <nav class="breadcrumb mb-0">

                @if ($topbarMenu && $topbarMenu->items->isNotEmpty())
                    @foreach ($topbarMenu->items as $item)
                        @php
                            $href = $item->url ?? ($item->page ? $item->page->url() : '#');
                        @endphp
                        <a class="breadcrumb-item small text-body" href="{{ $href }}" target="{{ $item->target }}">
                            @if ($item->icon)<i class="bi {{ $item->icon }} me-1"></i>@endif{{ $item->label }}
                        </a>
                    @endforeach
                @else
                    <a class="breadcrumb-item small text-body" href="#">
                        Career
                    </a>

                    <a class="breadcrumb-item small text-body" href="#">
                        Support
                    </a>

                    <a class="breadcrumb-item small text-body" href="#">
                        Terms
                    </a>

                    <a class="breadcrumb-item small text-body" href="#">
                        FAQs
                    </a>
                @endif

            </nav>

        </div>
    </div>
</div>
<!-- Topbar End -->