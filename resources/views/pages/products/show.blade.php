@extends('layouts.app')

@section('title', $product->meta_title ?? ($product->title . ' - Poised Technology'))

@section('meta_description', $product->meta_description ?? $product->short_description ?? '')

@section('content')

    @php use App\Cms\Content; @endphp

    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 mb-5 wow fadeIn"
        style="background: linear-gradient(rgba(0,0,0,.7), rgba(0,0,0,.7)), url('{{ $product->featuredImage->url ?? asset('assets/img/carousel-1.png') }}') center center no-repeat; background-size: cover;">
        <div class="container text-center py-5 mt-4">
            <h1 class="display-3 text-white mb-3 animated slideInDown">{{ $product->title }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                    <li class="breadcrumb-item text-primary active">{{ $product->title }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5">

                {{-- Gallery --}}
                <div class="col-lg-6">
                    @php
                        $galleryImages = $product->gallery->isNotEmpty()
                            ? $product->gallery
                            : ($product->featuredImage ? collect([(object) ['media' => $product->featuredImage]]) : collect());
                    @endphp

                    @if ($galleryImages->isNotEmpty())
                        <div id="productGallery" class="carousel slide mb-3" data-bs-ride="false">
                            <div class="carousel-inner rounded">
                                @foreach ($galleryImages as $index => $item)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ $item->media->url }}" class="d-block w-100" alt="{{ $product->title }}" style="aspect-ratio: 1/1; object-fit: cover;">
                                    </div>
                                @endforeach
                            </div>
                            @if ($galleryImages->count() > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#productGallery" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#productGallery" data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </button>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Title / short description / inquiry --}}
                <div class="col-lg-6">
                    @if ($product->category)
                        <span class="badge bg-light text-dark mb-2">{{ $product->category->name }}</span>
                    @endif

                    <h1 class="display-6 mb-3">{{ $product->title }}</h1>

                    @if ($product->short_description)
                        <p class="fs-5 text-muted mb-4">{{ $product->short_description }}</p>
                    @endif

                    <a href="#inquiry" class="btn btn-primary py-3 px-5">
                        <i class="bi bi-envelope me-2"></i> Request a Quote / Inquiry
                    </a>

                    @if ($product->documents->isNotEmpty())
                        <div class="mt-4">
                            <h6>Downloads</h6>
                            @foreach ($product->documents as $doc)
                                <a href="{{ $doc->media->url }}" target="_blank" class="btn btn-outline-secondary btn-sm me-2 mb-2">
                                    <i class="bi bi-file-earmark-pdf me-1"></i> {{ $doc->caption ?: $doc->media->filename }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    @if ($product->description)
        <!-- Full Description Start -->
        <div class="container-fluid py-5 bg-light">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-9">
                        {!! Content::richtext($product->description) !!}
                    </div>
                </div>
            </div>
        </div>
        <!-- Full Description End -->
    @endif

    @if (! empty($product->features))
        <!-- Features Start -->
        <div class="container-fluid py-5">
            <div class="container">
                <h2 class="text-center mb-5">Key Features</h2>
                <div class="row g-4">
                    @foreach ($product->features as $feature)
                        <div class="col-lg-4 col-md-6">
                            <div class="feature-item border rounded p-4 h-100 text-center">
                                @if (! empty($feature['icon']))
                                    <div class="icon-box-primary mx-auto mb-4">
                                        <i class="bi {{ $feature['icon'] }} text-dark"></i>
                                    </div>
                                @endif
                                <h5>{{ $feature['title'] ?? '' }}</h5>
                                @if (! empty($feature['description']))
                                    <p class="mb-0">{{ $feature['description'] }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- Features End -->
    @endif

    @if (! empty($product->specifications))
        <!-- Specifications Start -->
        <div class="container-fluid py-5 bg-light">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <h2 class="text-center mb-4">Specifications</h2>
                        <table class="table table-bordered bg-white">
                            <tbody>
                                @foreach ($product->specifications as $spec)
                                    <tr>
                                        <th class="w-25">{{ $spec['label'] ?? '' }}</th>
                                        <td>{{ $spec['value'] ?? '' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Specifications End -->
    @endif

    @if ($product->relatedProducts->isNotEmpty())
        <!-- Related Products Start -->
        <div class="container-fluid py-5">
            <div class="container">
                <h2 class="mb-4">Related Products</h2>
                <div class="row g-4">
                    @foreach ($product->relatedProducts as $related)
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('products.show', $related->slug) }}" class="text-decoration-none text-dark">
                                <div class="service-item h-100">
                                    @if ($related->featuredImage)
                                        <img src="{{ $related->featuredImage->url }}" alt="{{ $related->title }}"
                                            class="img-fluid rounded mb-3" style="aspect-ratio: 4/3; object-fit: cover; width: 100%;">
                                    @endif
                                    <h6 class="mb-0">{{ $related->title }}</h6>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- Related Products End -->
    @endif

    <!-- Inquiry Form Start -->
    <div class="container-fluid py-5 bg-dark text-white" id="inquiry">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="text-center mb-4">Inquire About {{ $product->title }}</h2>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('products.inquiry') }}">
                        @csrf
                        @include('partials.spam-protection-fields')
                        <input type="hidden" name="subject" value="Product Inquiry: {{ $product->title }}">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <input type="text" name="name" class="form-control" placeholder="Your Name" value="{{ old('name') }}">
                            </div>
                            <div class="col-sm-6">
                                <input type="email" name="email" class="form-control" placeholder="Your Email" value="{{ old('email') }}">
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="phone" class="form-control" placeholder="Your Phone" value="{{ old('phone') }}">
                            </div>
                            <div class="col-12">
                                <textarea name="message" class="form-control" rows="4" placeholder="Tell us what you need…">{{ old('message') }}</textarea>
                            </div>
                            <div class="col-12 text-center">
                                <button class="btn btn-primary px-5 py-3" type="submit">Send Inquiry</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Inquiry Form End -->

@endsection
