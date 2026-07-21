{{--
    Props: $bagName (the including form's named error bag — see
    MenuItemController::errorBagFor()/NEW_ITEM_ERROR_BAG — so errors here
    only ever show on the form that actually failed), $currentType
    (url|page|product|blog_post|news_article), $currentUrl,
    $currentPickerValue, $currentPageId.
    Relies on $pages/$products/$blogPosts/$newsArticles already being in
    scope from the including view (Blade @include shares parent data).
--}}

<div class="col-12">
    <label class="form-label">Link To</label>
    <select class="form-select js-link-type">
        <option value="url" @selected($currentType === 'url')>Custom URL</option>
        <option value="page" @selected($currentType === 'page')>Page</option>
        <option value="product" @selected($currentType === 'product')>Product</option>
        <option value="blog_post" @selected($currentType === 'blog_post')>Blog Post</option>
        <option value="news_article" @selected($currentType === 'news_article')>News Article</option>
    </select>
    <div class="form-text">Pick a Page, Product, Blog Post, or News Article — or enter a custom URL/path.</div>
</div>

<div class="col-md-6 js-link-field {{ $currentType === 'url' ? '' : 'd-none' }}" data-link-type="url">
    <label class="form-label">URL</label>
    <input type="text" name="url" class="form-control js-url-input"
        value="{{ $currentUrl }}" placeholder="/products or https://example.com">
    @error('url', $bagName)
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="col-md-6 js-link-field {{ $currentType === 'page' ? '' : 'd-none' }}" data-link-type="page">
    <label class="form-label">Page</label>
    <select name="page_id" class="form-select js-page-select">
        <option value="">— None —</option>
        @foreach ($pages as $page)
            <option value="{{ $page->id }}" @selected((string) $currentPageId === (string) $page->id)>
                {{ $page->title }}
            </option>
        @endforeach
    </select>
    @error('page_id', $bagName)
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="col-md-6 js-link-field {{ $currentType === 'product' ? '' : 'd-none' }}" data-link-type="product">
    <label class="form-label">Product</label>
    <select class="form-select js-content-picker-select">
        <option value="">— Select a product —</option>
        @foreach ($products as $product)
            <option value="{{ $product->url() }}" @selected($currentType === 'product' && $currentPickerValue === $product->url())>
                {{ $product->title }}
            </option>
        @endforeach
    </select>
    @if ($products->isEmpty())
        <div class="form-text">No products exist yet.</div>
    @endif
</div>

<div class="col-md-6 js-link-field {{ $currentType === 'blog_post' ? '' : 'd-none' }}" data-link-type="blog_post">
    <label class="form-label">Blog Post</label>
    <select class="form-select js-content-picker-select">
        <option value="">— Select a post —</option>
        @foreach ($blogPosts as $post)
            <option value="{{ $post->url() }}" @selected($currentType === 'blog_post' && $currentPickerValue === $post->url())>
                {{ $post->title }}
            </option>
        @endforeach
    </select>
    @if ($blogPosts->isEmpty())
        <div class="form-text">No blog posts exist yet.</div>
    @endif
</div>

<div class="col-md-6 js-link-field {{ $currentType === 'news_article' ? '' : 'd-none' }}" data-link-type="news_article">
    <label class="form-label">News Article</label>
    <select class="form-select js-content-picker-select">
        <option value="">— Select an article —</option>
        @foreach ($newsArticles as $article)
            <option value="{{ $article->url() }}" @selected($currentType === 'news_article' && $currentPickerValue === $article->url())>
                {{ $article->title }}
            </option>
        @endforeach
    </select>
    @if ($newsArticles->isEmpty())
        <div class="form-text">No news articles exist yet.</div>
    @endif
</div>
