@extends('layouts.app')

@section('title', 'News - Poised Technology')

@section('content')

    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 mb-5 wow fadeIn"
        style="background: linear-gradient(rgba(0,0,0,.7), rgba(0,0,0,.7)), url('{{ asset('assets/img/carousel-3.png') }}') center center no-repeat; background-size: cover;">
        <div class="container text-center py-5 mt-4">
            <h1 class="display-3 text-white mb-3 animated slideInDown">News</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item text-primary active">News</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    @if ($featuredArticle)
        <!-- Featured Article Start -->
        <div class="container-fluid py-5 bg-light">
            <div class="container">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-6">
                        @if ($featuredArticle->featuredImage)
                            <img src="{{ $featuredArticle->featuredImage->url }}" alt="{{ $featuredArticle->title }}"
                                class="img-fluid rounded" style="aspect-ratio: 16/9; object-fit: cover; width: 100%;">
                        @endif
                    </div>
                    <div class="col-lg-6">
                        <span class="badge bg-primary mb-2">Featured</span>
                        <h2 class="mb-3"><a href="{{ route('news.show', $featuredArticle->slug) }}" class="text-decoration-none text-dark">{{ $featuredArticle->title }}</a></h2>
                        <p class="text-muted">{{ $featuredArticle->excerpt }}</p>
                        <a href="{{ route('news.show', $featuredArticle->slug) }}" class="btn btn-primary">Read More</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Featured Article End -->
    @endif

    <!-- News Grid Start -->
    <div class="container-fluid py-5">
        <div class="container">

            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <form method="GET" class="d-flex gap-2">
                        <input type="text" name="search" class="form-control" placeholder="Search news…" value="{{ request('search') }}">
                        @if (request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                    </form>
                </div>
                <div class="col-lg-4">
                    <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                        <a href="{{ route('news.index', array_filter(['search' => request('search')])) }}"
                            class="btn btn-sm {{ ! request('category') ? 'btn-primary' : 'btn-outline-primary' }}">All</a>
                        @foreach ($categories as $category)
                            <a href="{{ route('news.index', array_filter(['search' => request('search'), 'category' => $category->slug])) }}"
                                class="btn btn-sm {{ request('category') === $category->slug ? 'btn-primary' : 'btn-outline-primary' }}">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="row g-4">
                @forelse ($articles as $article)
                    <div class="col-lg-4 col-md-6">
                        <a href="{{ route('news.show', $article->slug) }}" class="text-decoration-none text-dark">
                            <div class="service-item h-100">
                                @if ($article->featuredImage)
                                    <img src="{{ $article->featuredImage->url }}" alt="{{ $article->title }}"
                                        class="img-fluid rounded mb-3" style="aspect-ratio: 16/9; object-fit: cover; width: 100%;">
                                @endif
                                <h5 class="mb-2">{{ $article->title }}</h5>
                                <p class="small text-muted mb-2">{{ $article->published_at?->format('M j, Y') }}</p>
                                <p class="mb-0">{{ \Illuminate\Support\Str::limit($article->excerpt, 100) }}</p>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12"><p class="text-muted">No news found.</p></div>
                @endforelse
            </div>

            <div class="mt-4">{{ $articles->links() }}</div>

        </div>
    </div>
    <!-- News Grid End -->

@endsection
