@extends('layouts.app')

@section('title', 'Our Products - Poised Technology')

@section('content')

    @php use App\Cms\Content; @endphp

    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 mb-5 wow fadeIn"
        style="background: linear-gradient(rgba(0,0,0,.7), rgba(0,0,0,.7)), url('{{ asset('assets/img/carousel-1.png') }}') center center no-repeat; background-size: cover;">
        <div class="container text-center py-5 mt-4">
            <h1 class="display-3 text-white mb-3 animated slideInDown">Our Products</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item text-primary active">Products</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    @if ($featuredProducts->isNotEmpty())
        <!-- Featured Products Start -->
        <div class="container-fluid py-5 bg-light">
            <div class="container">
                <h2 class="mb-4">Featured Products</h2>
                <div class="row g-4">
                    @foreach ($featuredProducts as $product)
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">
                                <div class="service-item h-100">
                                    @if ($product->featuredImage)
                                        <img src="{{ $product->featuredImage->url }}" alt="{{ $product->title }}"
                                            class="img-fluid rounded mb-3" style="aspect-ratio: 4/3; object-fit: cover; width: 100%;">
                                    @endif
                                    <h5 class="mb-2">{{ $product->title }}</h5>
                                    <p class="small text-muted mb-0">{{ \Illuminate\Support\Str::limit($product->short_description, 80) }}</p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- Featured Products End -->
    @endif

    <!-- Products Grid Start -->
    <div class="container-fluid py-5">
        <div class="container">

            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <form method="GET" class="d-flex gap-2">
                        <input type="text" name="search" class="form-control" placeholder="Search products…" value="{{ request('search') }}">
                        @if (request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                    </form>
                </div>
                <div class="col-lg-4">
                    <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                        <a href="{{ route('products.index', array_filter(['search' => request('search')])) }}"
                            class="btn btn-sm {{ ! request('category') ? 'btn-primary' : 'btn-outline-primary' }}">All</a>
                        @foreach ($categories as $category)
                            <a href="{{ route('products.index', array_filter(['search' => request('search'), 'category' => $category->slug])) }}"
                                class="btn btn-sm {{ request('category') === $category->slug ? 'btn-primary' : 'btn-outline-primary' }}">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="row g-4">
                @forelse ($products as $product)
                    <div class="col-lg-4 col-md-6">
                        <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">
                            <div class="service-item h-100">
                                @if ($product->featuredImage)
                                    <img src="{{ $product->featuredImage->url }}" alt="{{ $product->title }}"
                                        class="img-fluid rounded mb-3" style="aspect-ratio: 4/3; object-fit: cover; width: 100%;">
                                @endif
                                <h4 class="mb-2">{{ $product->title }}</h4>
                                @if ($product->category)
                                    <span class="badge bg-light text-dark mb-2">{{ $product->category->name }}</span>
                                @endif
                                <p class="mb-0">{{ \Illuminate\Support\Str::limit($product->short_description, 120) }}</p>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-muted">No products found.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-4">{{ $products->links() }}</div>

        </div>
    </div>
    <!-- Products Grid End -->

    <!-- CTA Start -->
    <div class="container-fluid py-5 bg-dark text-white">
        <div class="container text-center">
            <h1 class="display-5 mb-4">Need Help Choosing the Right Product?</h1>
            <p class="fs-5 mb-4">Talk to our team for a tailored recommendation.</p>
            <a href="{{ route('contact') }}" class="btn btn-primary py-3 px-5">Contact Us</a>
        </div>
    </div>
    <!-- CTA End -->

@endsection
