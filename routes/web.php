<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContentMediaController;
use App\Http\Controllers\Admin\ForgotPasswordController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PageSectionController;
use App\Http\Controllers\Admin\SectionItemController;
use App\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\NewsArticleController as AdminNewsArticleController;
use App\Http\Controllers\Admin\NewsCategoryController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;

Route::get('/', fn () => app(PageController::class)->show('home', 'pages.home'))
    ->name('home');

Route::get('/about', fn () => app(PageController::class)->show('about', 'pages.about'))
    ->name('about');

Route::get('/solution', fn () => app(PageController::class)->show('solutions', 'pages.solutions'))
    ->name('solutions');

Route::get('/service', fn () => app(PageController::class)->show('services', 'pages.services'))
    ->name('services');

Route::get('/contact', fn () => app(PageController::class)->show('contact', 'pages.contact'))
    ->name('contact');

Route::post('/contact', fn (Request $request) => app(ContactMessageController::class)->store($request, 'contact'))
    ->middleware('throttle:10,1')
    ->name('contact.submit');

Route::post('/appointment', fn (Request $request) => app(ContactMessageController::class)->store($request, 'home'))
    ->middleware('throttle:10,1')
    ->name('appointment.submit');

Route::get('/products', [ProductController::class, 'index'])
    ->name('products.index');

Route::get('/products/{slug}', [ProductController::class, 'show'])
    ->where('slug', '[a-z0-9-]+')
    ->name('products.show');

Route::post('/products/inquiry', fn (Request $request) => app(ContactMessageController::class)->store($request, 'product-inquiry'))
    ->middleware('throttle:10,1')
    ->name('products.inquiry');

Route::get('/blog', [BlogController::class, 'index'])
    ->name('blog.index');

Route::get('/blog/{slug}', [BlogController::class, 'show'])
    ->where('slug', '[a-z0-9-]+')
    ->name('blog.show');

Route::get('/news', [NewsController::class, 'index'])
    ->name('news.index');

Route::get('/news/{slug}', [NewsController::class, 'show'])
    ->where('slug', '[a-z0-9-]+')
    ->name('news.show');



Route::prefix('admin')->group(function () {

    Route::middleware('guest')->group(function () {

        Route::get('/login', [AuthController::class, 'login'])
            ->name('admin.login');

        Route::post('/login', [AuthController::class, 'loginSubmit'])
            ->middleware('throttle:5,1')
            ->name('admin.login.submit');

        Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
            ->name('admin.password.request');

        Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])
            ->middleware('throttle:5,1')
            ->name('admin.password.email');

        Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])
            ->name('admin.password.reset');

        Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])
            ->name('admin.password.update');

    });

    Route::middleware('auth')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('admin.dashboard');

        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('admin.logout');

        Route::get('/media', [MediaController::class, 'index'])
            ->name('admin.media.index');

        Route::get('/media/modal-items', [MediaController::class, 'modalItems'])
            ->name('admin.media.modal-items');

        Route::post('/media', [MediaController::class, 'store'])
            ->name('admin.media.store');

        Route::patch('/media/{media}', [MediaController::class, 'update'])
            ->name('admin.media.update');

        Route::delete('/media/{media}', [MediaController::class, 'destroy'])
            ->name('admin.media.destroy');

        Route::get('/settings', [SettingController::class, 'index'])
            ->name('admin.settings.index');

        Route::patch('/settings', [SettingController::class, 'update'])
            ->name('admin.settings.update');

        Route::get('/pages', [AdminPageController::class, 'index'])
            ->name('admin.pages.index');

        Route::get('/pages/create', [AdminPageController::class, 'create'])
            ->name('admin.pages.create');

        Route::post('/pages', [AdminPageController::class, 'store'])
            ->name('admin.pages.store');

        Route::get('/pages/{page}', [AdminPageController::class, 'edit'])
            ->name('admin.pages.edit');

        Route::patch('/pages/{page}', [AdminPageController::class, 'update'])
            ->name('admin.pages.update');

        Route::delete('/pages/{page}', [AdminPageController::class, 'destroy'])
            ->name('admin.pages.destroy');

        Route::get('/pages/{page}/history', [AdminPageController::class, 'history'])
            ->name('admin.pages.history');

        Route::post('/pages/{page}/revisions/{revision}/restore', [AdminPageController::class, 'restoreRevision'])
            ->name('admin.pages.revisions.restore');

        Route::patch('/page-sections/{section}', [PageSectionController::class, 'update'])
            ->name('admin.page-sections.update');

        Route::post('/page-sections/{section}/items', [SectionItemController::class, 'store'])
            ->name('admin.section-items.store');

        Route::patch('/section-items/{item}', [SectionItemController::class, 'update'])
            ->name('admin.section-items.update');

        Route::delete('/section-items/{item}', [SectionItemController::class, 'destroy'])
            ->name('admin.section-items.destroy');

        Route::post('/section-items/{item}/move', [SectionItemController::class, 'move'])
            ->name('admin.section-items.move');

        Route::post('/content-media/{type}/{id}', [ContentMediaController::class, 'store'])
            ->where('type', 'product|blog_post|news_article')
            ->where('id', '[0-9]+')
            ->name('admin.content-media.store');

        Route::patch('/content-media/{contentMedia}', [ContentMediaController::class, 'update'])
            ->name('admin.content-media.update');

        Route::delete('/content-media/{contentMedia}', [ContentMediaController::class, 'destroy'])
            ->name('admin.content-media.destroy');

        Route::post('/content-media/{contentMedia}/move', [ContentMediaController::class, 'move'])
            ->name('admin.content-media.move');

        Route::get('/products', [AdminProductController::class, 'index'])
            ->name('admin.products.index');

        Route::get('/products/create', [AdminProductController::class, 'create'])
            ->name('admin.products.create');

        Route::post('/products', [AdminProductController::class, 'store'])
            ->name('admin.products.store');

        Route::get('/products/categories', [ProductCategoryController::class, 'index'])
            ->name('admin.product-categories.index');

        Route::get('/products/categories/create', [ProductCategoryController::class, 'create'])
            ->name('admin.product-categories.create');

        Route::post('/products/categories', [ProductCategoryController::class, 'store'])
            ->name('admin.product-categories.store');

        Route::get('/products/categories/{productCategory}', [ProductCategoryController::class, 'edit'])
            ->name('admin.product-categories.edit');

        Route::patch('/products/categories/{productCategory}', [ProductCategoryController::class, 'update'])
            ->name('admin.product-categories.update');

        Route::delete('/products/categories/{productCategory}', [ProductCategoryController::class, 'destroy'])
            ->name('admin.product-categories.destroy');

        Route::get('/products/{product}', [AdminProductController::class, 'edit'])
            ->name('admin.products.edit');

        Route::patch('/products/{product}', [AdminProductController::class, 'update'])
            ->name('admin.products.update');

        Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])
            ->name('admin.products.destroy');

        Route::get('/products/{product}/history', [AdminProductController::class, 'history'])
            ->name('admin.products.history');

        Route::post('/products/{product}/revisions/{revision}/restore', [AdminProductController::class, 'restoreRevision'])
            ->name('admin.products.revisions.restore');

        Route::get('/blog', [BlogPostController::class, 'index'])
            ->name('admin.blog-posts.index');

        Route::get('/blog/create', [BlogPostController::class, 'create'])
            ->name('admin.blog-posts.create');

        Route::post('/blog', [BlogPostController::class, 'store'])
            ->name('admin.blog-posts.store');

        Route::get('/blog/categories', [BlogCategoryController::class, 'index'])
            ->name('admin.blog-categories.index');

        Route::get('/blog/categories/create', [BlogCategoryController::class, 'create'])
            ->name('admin.blog-categories.create');

        Route::post('/blog/categories', [BlogCategoryController::class, 'store'])
            ->name('admin.blog-categories.store');

        Route::get('/blog/categories/{blogCategory}', [BlogCategoryController::class, 'edit'])
            ->name('admin.blog-categories.edit');

        Route::patch('/blog/categories/{blogCategory}', [BlogCategoryController::class, 'update'])
            ->name('admin.blog-categories.update');

        Route::delete('/blog/categories/{blogCategory}', [BlogCategoryController::class, 'destroy'])
            ->name('admin.blog-categories.destroy');

        Route::get('/blog/{blogPost}', [BlogPostController::class, 'edit'])
            ->name('admin.blog-posts.edit');

        Route::patch('/blog/{blogPost}', [BlogPostController::class, 'update'])
            ->name('admin.blog-posts.update');

        Route::delete('/blog/{blogPost}', [BlogPostController::class, 'destroy'])
            ->name('admin.blog-posts.destroy');

        Route::get('/blog/{blogPost}/history', [BlogPostController::class, 'history'])
            ->name('admin.blog-posts.history');

        Route::post('/blog/{blogPost}/revisions/{revision}/restore', [BlogPostController::class, 'restoreRevision'])
            ->name('admin.blog-posts.revisions.restore');

        Route::get('/news', [AdminNewsArticleController::class, 'index'])
            ->name('admin.news-articles.index');

        Route::get('/news/create', [AdminNewsArticleController::class, 'create'])
            ->name('admin.news-articles.create');

        Route::post('/news', [AdminNewsArticleController::class, 'store'])
            ->name('admin.news-articles.store');

        Route::get('/news/categories', [NewsCategoryController::class, 'index'])
            ->name('admin.news-categories.index');

        Route::get('/news/categories/create', [NewsCategoryController::class, 'create'])
            ->name('admin.news-categories.create');

        Route::post('/news/categories', [NewsCategoryController::class, 'store'])
            ->name('admin.news-categories.store');

        Route::get('/news/categories/{newsCategory}', [NewsCategoryController::class, 'edit'])
            ->name('admin.news-categories.edit');

        Route::patch('/news/categories/{newsCategory}', [NewsCategoryController::class, 'update'])
            ->name('admin.news-categories.update');

        Route::delete('/news/categories/{newsCategory}', [NewsCategoryController::class, 'destroy'])
            ->name('admin.news-categories.destroy');

        Route::get('/news/{newsArticle}', [AdminNewsArticleController::class, 'edit'])
            ->name('admin.news-articles.edit');

        Route::patch('/news/{newsArticle}', [AdminNewsArticleController::class, 'update'])
            ->name('admin.news-articles.update');

        Route::delete('/news/{newsArticle}', [AdminNewsArticleController::class, 'destroy'])
            ->name('admin.news-articles.destroy');

        Route::get('/news/{newsArticle}/history', [AdminNewsArticleController::class, 'history'])
            ->name('admin.news-articles.history');

        Route::post('/news/{newsArticle}/revisions/{revision}/restore', [AdminNewsArticleController::class, 'restoreRevision'])
            ->name('admin.news-articles.revisions.restore');

        Route::get('/menus', [MenuController::class, 'index'])
            ->name('admin.menus.index');

        Route::get('/menus/{menu}', [MenuController::class, 'edit'])
            ->name('admin.menus.edit');

        Route::post('/menus/{menu}/items', [MenuItemController::class, 'store'])
            ->name('admin.menu-items.store');

        Route::patch('/menu-items/{menuItem}', [MenuItemController::class, 'update'])
            ->name('admin.menu-items.update');

        Route::delete('/menu-items/{menuItem}', [MenuItemController::class, 'destroy'])
            ->name('admin.menu-items.destroy');

        Route::post('/menu-items/{menuItem}/move', [MenuItemController::class, 'move'])
            ->name('admin.menu-items.move');

        Route::get('/contact-messages', [AdminContactMessageController::class, 'index'])
            ->name('admin.contact-messages.index');

        Route::get('/contact-messages/{contactMessage}', [AdminContactMessageController::class, 'show'])
            ->name('admin.contact-messages.show');

        Route::patch('/contact-messages/{contactMessage}/archive', [AdminContactMessageController::class, 'archive'])
            ->name('admin.contact-messages.archive');

    });

});

// Catch-all for admin-created pages (career/support/terms/faq/generic templates
// and any future template), which have no hand-written Blade view. Must stay
// LAST so it never shadows a named route above — those 5 pages keep rendering
// via PageController::show() exactly as before.
Route::get('/{slug}', fn (string $slug) => app(PageController::class)->showDynamic($slug))
    ->where('slug', '[a-z0-9-]+')
    ->name('page.dynamic');