@php
    use App\Cms\Content;

    $checklistItems = Content::items($section, []);
@endphp

@if ($checklistItems->isNotEmpty())
    <!-- Checklist Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-3 justify-content-center">
                @foreach ($checklistItems as $item)
                    <div class="col-sm-6 col-lg-4">
                        <div class="d-flex align-items-start">
                            <i class="bi {{ Content::itemField($item, 'icon', 'bi-check-circle-fill') }} text-primary fs-4 me-3"></i>
                            <div>
                                <span>{{ Content::itemField($item, 'text') }}</span>
                                @if ($description = Content::itemField($item, 'description'))
                                    <br><small class="text-muted">{!! Content::richtext($description) !!}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Checklist End -->
@endif
