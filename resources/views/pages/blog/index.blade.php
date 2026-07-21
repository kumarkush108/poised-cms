@extends('layouts.app')

@section('title', 'Blog - Poised Technology')

@section('content')

    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 mb-5 wow fadeIn"
        style="background: linear-gradient(rgba(0,0,0,.7), rgba(0,0,0,.7)), url('{{ asset('assets/img/carousel-2.png') }}') center center no-repeat; background-size: cover;">
        <div class="container text-center py-5 mt-4">
            <h1 class="display-3 text-white mb-3 animated slideInDown">Blog</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item text-primary active">Blog</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    @if ($featuredPost)
        <!-- Featured Post Start -->
        <div class="container-fluid py-5 bg-light">
            <div class="container">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-6">
                        @if ($featuredPost->featuredImage)
                            <img src="{{ $featuredPost->featuredImage->url }}" alt="{{ $featuredPost->title }}"
                                class="img-fluid rounded" style="aspect-ratio: 16/9; object-fit: cover; width: 100%;">
                        @endif
                    </div>
                    <div class="col-lg-6">
                        <span class="badge bg-primary mb-2">Featured</span>
                        <h2 class="mb-3"><a href="{{ route('blog.show', $featuredPost->slug) }}" class="text-decoration-none text-dark">{{ $featuredPost->title }}</a></h2>
                        <p class="text-muted">{{ $featuredPost->excerpt }}</p>
                        <a href="{{ route('blog.show', $featuredPost->slug) }}" class="btn btn-primary">Read More</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Featured Post End -->
    @endif

    <!-- Blog Grid Start -->
    <div class="container-fluid py-5">
        <div class="container">

            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <form method="GET" class="d-flex gap-2">
                        <input type="text" name="search" class="form-control" placeholder="Search posts…" value="{{ request('search') }}">
                        @if (request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                    </form>
                </div>
                <div class="col-lg-4">
                    <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                        <a href="{{ route('blog.index', array_filter(['search' => request('search')])) }}"
                            class="btn btn-sm {{ ! request('category') ? 'btn-primary' : 'btn-outline-primary' }}">All</a>
                        @foreach ($categories as $category)
                            <a href="{{ route('blog.index', array_filter(['search' => request('search'), 'category' => $category->slug])) }}"
                                class="btn btn-sm {{ request('category') === $category->slug ? 'btn-primary' : 'btn-outline-primary' }}">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="row g-4">
                @forelse ($posts as $post)
                    <div class="col-lg-4 col-md-6">
                        <div class="service-item h-100">
                            <a href="{{ route('blog.show', $post->slug) }}" class="text-decoration-none text-dark">
                                @if ($post->featuredImage)
                                    <img src="{{ $post->featuredImage->url }}" alt="{{ $post->title }}"
                                        class="img-fluid rounded mb-3" style="aspect-ratio: 16/9; object-fit: cover; width: 100%;">
                                @endif
                                <h5 class="mb-2">{{ $post->title }}</h5>
                            </a>
                            <p class="small text-muted mb-2">
                                {{ $post->published_at?->format('M j, Y') }} &middot; {{ $post->reading_time }} min read
                            </p>
                            <p class="mb-2">{{ \Illuminate\Support\Str::limit($post->excerpt, 100) }}</p>
                            @if ($post->tags->isNotEmpty())
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach ($post->tags as $tag)
                                        <span class="badge bg-light text-dark">{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-12"><p class="text-muted">No posts found.</p></div>
                @endforelse
            </div>

            <div class="mt-4">{{ $posts->links() }}</div>

        </div>
    </div>
    <!-- Blog Grid End -->

@endsection
