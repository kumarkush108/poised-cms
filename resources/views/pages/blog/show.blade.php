@extends('layouts.app')

@section('title', $post->meta_title ?? ($post->title . ' - Poised Technology'))

@section('meta_description', $post->meta_description ?? $post->excerpt ?? '')

@section('content')

    @php
        use App\Cms\Content;
        $shareUrl = urlencode(url()->current());
        $shareTitle = urlencode($post->title);
    @endphp

    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 mb-5 wow fadeIn"
        style="background: linear-gradient(rgba(0,0,0,.7), rgba(0,0,0,.7)), url('{{ $post->featuredImage->url ?? asset('assets/img/carousel-2.png') }}') center center no-repeat; background-size: cover;">
        <div class="container text-center py-5 mt-4">
            <h1 class="display-4 text-white mb-3 animated slideInDown">{{ $post->title }}</h1>
            <p class="text-white">
                @if ($post->author_name)
                    <i class="bi bi-person-circle me-1"></i> {{ $post->author_name }} &middot;
                @endif
                <i class="bi bi-calendar3 me-1"></i> {{ $post->published_at?->format('M j, Y') }} &middot;
                <i class="bi bi-clock me-1"></i> {{ $post->reading_time }} min read
            </p>
        </div>
    </div>
    <!-- Page Header End -->

    <div class="container-fluid py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">

                    {!! Content::richtext($post->body) !!}

                    @if ($post->tags->isNotEmpty())
                        <div class="d-flex flex-wrap gap-2 mt-4">
                            @foreach ($post->tags as $tag)
                                <span class="badge bg-light text-dark">#{{ $tag->name }}</span>
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

                    {{-- Comments are intentionally not implemented yet — a future
                         polymorphic Comment model (commentable_type/commentable_id)
                         could attach here without changing anything above. --}}

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
                            <a href="{{ route('blog.show', $previous->slug) }}" class="text-decoration-none text-dark">
                                <small class="text-muted d-block">&larr; Previous</small>
                                <strong>{{ $previous->title }}</strong>
                            </a>
                        @endif
                    </div>
                    <div class="col-6 text-end">
                        @if ($next)
                            <a href="{{ route('blog.show', $next->slug) }}" class="text-decoration-none text-dark">
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
        <!-- Related Posts Start -->
        <div class="container-fluid py-5 bg-light">
            <div class="container">
                <h2 class="mb-4">Related Posts</h2>
                <div class="row g-4">
                    @foreach ($related as $relatedPost)
                        <div class="col-lg-4 col-md-6">
                            <a href="{{ route('blog.show', $relatedPost->slug) }}" class="text-decoration-none text-dark">
                                <div class="service-item h-100">
                                    @if ($relatedPost->featuredImage)
                                        <img src="{{ $relatedPost->featuredImage->url }}" alt="{{ $relatedPost->title }}"
                                            class="img-fluid rounded mb-3" style="aspect-ratio: 16/9; object-fit: cover; width: 100%;">
                                    @endif
                                    <h6 class="mb-0">{{ $relatedPost->title }}</h6>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- Related Posts End -->
    @endif

@endsection
