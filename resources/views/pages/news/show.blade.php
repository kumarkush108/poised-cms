@extends('layouts.app')

@section('title', $article->meta_title ?? ($article->title . ' - Poised Technology'))

@section('meta_description', $article->meta_description ?? $article->excerpt ?? '')

@section('content')

    @php
        use App\Cms\Content;
        $shareUrl = urlencode(url()->current());
        $shareTitle = urlencode($article->title);
    @endphp

    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 mb-5 wow fadeIn"
        style="background: linear-gradient(rgba(0,0,0,.7), rgba(0,0,0,.7)), url('{{ $article->featuredImage->url ?? asset('assets/img/carousel-3.png') }}') center center no-repeat; background-size: cover;">
        <div class="container text-center py-5 mt-4">
            <h1 class="display-4 text-white mb-3 animated slideInDown">{{ $article->title }}</h1>
            <p class="text-white">
                <i class="bi bi-calendar3 me-1"></i> {{ $article->published_at?->format('M j, Y') }}
                @if ($article->category)
                    &middot; {{ $article->category->name }}
                @endif
            </p>
        </div>
    </div>
    <!-- Page Header End -->

    <div class="container-fluid py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">

                    {!! Content::richtext($article->body) !!}

                    @if ($article->gallery->isNotEmpty())
                        <!-- News Gallery Start -->
                        <div class="row g-3 mt-4">
                            @foreach ($article->gallery as $item)
                                <div class="col-md-4">
                                    <img src="{{ $item->media->url }}" alt="{{ $item->caption ?? $article->title }}"
                                        class="img-fluid rounded" style="aspect-ratio: 4/3; object-fit: cover; width: 100%;">
                                </div>
                            @endforeach
                        </div>
                        <!-- News Gallery End -->
                    @endif

                    @if ($article->documents->isNotEmpty())
                        <div class="mt-4">
                            <h6>Attachments</h6>
                            @foreach ($article->documents as $doc)
                                <a href="{{ $doc->media->url }}" target="_blank" class="btn btn-outline-secondary btn-sm me-2 mb-2">
                                    <i class="bi bi-file-earmark-text me-1"></i> {{ $doc->caption ?: $doc->media->filename }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                    <!-- Social Share Start -->
                    <div class="d-flex align-items-center gap-2 mt-4 pt-4 border-top">
                        <span class="text-muted me-2">Share:</span>
                        <a class="btn btn-sm btn-outline-secondary" target="_blank" rel="noopener"
                            href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}"><i class="bi bi-facebook"></i></a>
                        <a class="btn btn-sm btn-outline-secondary" target="_blank" rel="noopener"
                            href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}"><i class="bi bi-twitter-x"></i></a>
                        <a class="btn btn-sm btn-outline-secondary" target="_blank" rel="noopener"
                            href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}"><i class="bi bi-linkedin"></i></a>
                        <a class="btn btn-sm btn-outline-secondary" target="_blank" rel="noopener"
                            href="https://wa.me/?text={{ $shareTitle }}%20{{ $shareUrl }}"><i class="bi bi-whatsapp"></i></a>
                    </div>
                    <!-- Social Share End -->

                </div>
            </div>
        </div>
    </div>

    <!-- Prev/Next Start -->
    @if ($previous || $next)
        <div class="container-fluid py-4 border-top">
            <div class="container">
                <div class="row">
                    <div class="col-6">
                        @if ($previous)
                            <a href="{{ route('news.show', $previous->slug) }}" class="text-decoration-none text-dark">
                                <small class="text-muted d-block">&larr; Previous</small>
                                <strong>{{ $previous->title }}</strong>
                            </a>
                        @endif
                    </div>
                    <div class="col-6 text-end">
                        @if ($next)
                            <a href="{{ route('news.show', $next->slug) }}" class="text-decoration-none text-dark">
                                <small class="text-muted d-block">Next &rarr;</small>
                                <strong>{{ $next->title }}</strong>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- Prev/Next End -->

    @if ($related->isNotEmpty())
        <!-- Related News Start -->
        <div class="container-fluid py-5 bg-light">
            <div class="container">
                <h2 class="mb-4">Related News</h2>
                <div class="row g-4">
                    @foreach ($related as $relatedArticle)
                        <div class="col-lg-4 col-md-6">
                            <a href="{{ route('news.show', $relatedArticle->slug) }}" class="text-decoration-none text-dark">
                                <div class="service-item h-100">
                                    @if ($relatedArticle->featuredImage)
                                        <img src="{{ $relatedArticle->featuredImage->url }}" alt="{{ $relatedArticle->title }}"
                                            class="img-fluid rounded mb-3" style="aspect-ratio: 16/9; object-fit: cover; width: 100%;">
                                    @endif
                                    <h6 class="mb-0">{{ $relatedArticle->title }}</h6>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- Related News End -->
    @endif

@endsection
