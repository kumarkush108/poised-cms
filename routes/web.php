<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminPermissionController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContentMediaController;
use App\Http\Controllers\Admin\ForgotPasswordController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PageSectionController;
use App\Http\Controllers\Admin\ProfileController;
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
    ->middleware(['throttle:public-form', 'spam-protection'])
    ->name('contact.submit');

Route::post('/appointment', fn (Request $request) => app(ContactMessageController::class)->store($request, 'home'))
    ->middleware(['throttle:public-form', 'spam-protection'])
    ->name('appointment.submit');

Route::get('/products', [ProductController::class, 'index'])
    ->name('products.index');

Route::get('/products/{slug}', [ProductController::class, 'show'])
    ->where('slug', '[a-z0-9-]+')
    ->name('products.show');

Route::post('/products/inquiry', fn (Request $request) => app(ContactMessageController::class)->store($request, 'product-inquiry'))
    ->middleware(['throttle:public-form', 'spam-protection'])
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
            ->middleware('permission:dashboard,view')
            ->name('admin.dashboard');

        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('admin.logout');

        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('admin.profile.edit');

        Route::patch('/profile', [ProfileController::class, 'update'])
            ->name('admin.profile.update');

        Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])
            ->name('admin.profile.password');

        Route::get('/media', [MediaController::class, 'index'])
            ->middleware('permission:media,view')
            ->name('admin.media.index');

        Route::get('/media/modal-items', [MediaController::class, 'modalItems'])
            ->middleware('permission:media,view')
            ->name('admin.media.modal-items');

        Route::post('/media', [MediaController::class, 'store'])
            ->middleware('permission:media,upload')
            ->name('admin.media.store');

        Route::patch('/media/{media}', [MediaController::class, 'update'])
            ->middleware('permission:media,edit')
            ->name('admin.media.update');

        Route::delete('/media/{media}', [MediaController::class, 'destroy'])
            ->middleware('permission:media,delete')
            ->name('admin.media.destroy');

        Route::get('/settings', [SettingController::class, 'index'])
            ->middleware('permission:settings,view')
            ->name('admin.settings.index');

        Route::patch('/settings', [SettingController::class, 'update'])
            ->middleware('permission:settings,edit')
            ->name('admin.settings.update');

        Route::get('/pages', [AdminPageController::class, 'index'])
            ->middleware('permission:pages,view')
            ->name('admin.pages.index');

        Route::get('/pages/create', [AdminPageController::class, 'create'])
            ->middleware('permission:pages,create')
            ->name('admin.pages.create');

        Route::post('/pages', [AdminPageController::class, 'store'])
            ->middleware('permission:pages,create')
            ->name('admin.pages.store');

        Route::get('/pages/{page}', [AdminPageController::class, 'edit'])
            ->middleware('permission:pages,edit')
            ->name('admin.pages.edit');

        Route::patch('/pages/{page}', [AdminPageController::class, 'update'])
            ->middleware('permission:pages,edit')
            ->name('admin.pages.update');

        Route::delete('/pages/{page}', [AdminPageController::class, 'destroy'])
            ->middleware('permission:pages,delete')
            ->name('admin.pages.destroy');

        Route::get('/pages/{page}/history', [AdminPageController::class, 'history'])
            ->middleware('permission:pages,edit')
            ->name('admin.pages.history');

        Route::post('/pages/{page}/revisions/{revision}/restore', [AdminPageController::class, 'restoreRevision'])
            ->middleware('permission:pages,edit')
            ->name('admin.pages.revisions.restore');

        Route::patch('/page-sections/{section}', [PageSectionController::class, 'update'])
            ->middleware('permission:pages,edit')
            ->name('admin.page-sections.update');

        Route::post('/page-sections/{section}/items', [SectionItemController::class, 'store'])
            ->middleware('permission:pages,edit')
            ->name('admin.section-items.store');

        Route::patch('/section-items/{item}', [SectionItemController::class, 'update'])
            ->middleware('permission:pages,edit')
            ->name('admin.section-items.update');

        Route::delete('/section-items/{item}', [SectionItemController::class, 'destroy'])
            ->middleware('permission:pages,edit')
            ->name('admin.section-items.destroy');

        Route::post('/section-items/{item}/move', [SectionItemController::class, 'move'])
            ->middleware('permission:pages,edit')
            ->name('admin.section-items.move');

        // ContentMediaController resolves its module from the {type}/mediable_type
        // param at the controller level (product/blog_post/news_article aren't
        // known to route middleware ahead of model resolution) — see
        // ContentMediaController::TYPE_MODULES.
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
            ->middleware('permission:products,view')
            ->name('admin.products.index');

        Route::get('/products/create', [AdminProductController::class, 'create'])
            ->middleware('permission:products,create')
            ->name('admin.products.create');

        Route::post('/products', [AdminProductController::class, 'store'])
            ->middleware('permission:products,create')
            ->name('admin.products.store');

        Route::get('/products/categories', [ProductCategoryController::class, 'index'])
            ->middleware('permission:product_categories,view')
            ->name('admin.product-categories.index');

        Route::get('/products/categories/create', [ProductCategoryController::class, 'create'])
            ->middleware('permission:product_categories,create')
            ->name('admin.product-categories.create');

        Route::post('/products/categories', [ProductCategoryController::class, 'store'])
            ->middleware('permission:product_categories,create')
            ->name('admin.product-categories.store');

        Route::get('/products/categories/{productCategory}', [ProductCategoryController::class, 'edit'])
            ->middleware('permission:product_categories,edit')
            ->name('admin.product-categories.edit');

        Route::patch('/products/categories/{productCategory}', [ProductCategoryController::class, 'update'])
            ->middleware('permission:product_categories,edit')
            ->name('admin.product-categories.update');

        Route::delete('/products/categories/{productCategory}', [ProductCategoryController::class, 'destroy'])
            ->middleware('permission:product_categories,delete')
            ->name('admin.product-categories.destroy');

        Route::get('/products/{product}', [AdminProductController::class, 'edit'])
            ->middleware('permission:products,edit')
            ->name('admin.products.edit');

        Route::patch('/products/{product}', [AdminProductController::class, 'update'])
            ->middleware('permission:products,edit')
            ->name('admin.products.update');

        Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])
            ->middleware('permission:products,delete')
            ->name('admin.products.destroy');

        Route::get('/products/{product}/history', [AdminProductController::class, 'history'])
            ->middleware('permission:products,edit')
            ->name('admin.products.history');

        Route::post('/products/{product}/revisions/{revision}/restore', [AdminProductController::class, 'restoreRevision'])
            ->middleware('permission:products,edit')
            ->name('admin.products.revisions.restore');

        Route::get('/blog', [BlogPostController::class, 'index'])
            ->middleware('permission:blogs,view')
            ->name('admin.blog-posts.index');

        Route::get('/blog/create', [BlogPostController::class, 'create'])
            ->middleware('permission:blogs,create')
            ->name('admin.blog-posts.create');

        Route::post('/blog', [BlogPostController::class, 'store'])
            ->middleware('permission:blogs,create')
            ->name('admin.blog-posts.store');

        Route::get('/blog/categories', [BlogCategoryController::class, 'index'])
            ->middleware('permission:blog_categories,view')
            ->name('admin.blog-categories.index');

        Route::get('/blog/categories/create', [BlogCategoryController::class, 'create'])
            ->middleware('permission:blog_categories,create')
            ->name('admin.blog-categories.create');

        Route::post('/blog/categories', [BlogCategoryController::class, 'store'])
            ->middleware('permission:blog_categories,create')
            ->name('admin.blog-categories.store');

        Route::get('/blog/categories/{blogCategory}', [BlogCategoryController::class, 'edit'])
            ->middleware('permission:blog_categories,edit')
            ->name('admin.blog-categories.edit');

        Route::patch('/blog/categories/{blogCategory}', [BlogCategoryController::class, 'update'])
            ->middleware('permission:blog_categories,edit')
            ->name('admin.blog-categories.update');

        Route::delete('/blog/categories/{blogCategory}', [BlogCategoryController::class, 'destroy'])
            ->middleware('permission:blog_categories,delete')
            ->name('admin.blog-categories.destroy');

        Route::get('/blog/{blogPost}', [BlogPostController::class, 'edit'])
            ->middleware('permission:blogs,edit')
            ->name('admin.blog-posts.edit');

        Route::patch('/blog/{blogPost}', [BlogPostController::class, 'update'])
            ->middleware('permission:blogs,edit')
            ->name('admin.blog-posts.update');

        Route::delete('/blog/{blogPost}', [BlogPostController::class, 'destroy'])
            ->middleware('permission:blogs,delete')
            ->name('admin.blog-posts.destroy');

        Route::get('/blog/{blogPost}/history', [BlogPostController::class, 'history'])
            ->middleware('permission:blogs,edit')
            ->name('admin.blog-posts.history');

        Route::post('/blog/{blogPost}/revisions/{revision}/restore', [BlogPostController::class, 'restoreRevision'])
            ->middleware('permission:blogs,edit')
            ->name('admin.blog-posts.revisions.restore');

        Route::get('/news', [AdminNewsArticleController::class, 'index'])
            ->middleware('permission:news,view')
            ->name('admin.news-articles.index');

        Route::get('/news/create', [AdminNewsArticleController::class, 'create'])
            ->middleware('permission:news,create')
            ->name('admin.news-articles.create');

        Route::post('/news', [AdminNewsArticleController::class, 'store'])
            ->middleware('permission:news,create')
            ->name('admin.news-articles.store');

        Route::get('/news/categories', [NewsCategoryController::class, 'index'])
            ->middleware('permission:news_categories,view')
            ->name('admin.news-categories.index');

        Route::get('/news/categories/create', [NewsCategoryController::class, 'create'])
            ->middleware('permission:news_categories,create')
            ->name('admin.news-categories.create');

        Route::post('/news/categories', [NewsCategoryController::class, 'store'])
            ->middleware('permission:news_categories,create')
            ->name('admin.news-categories.store');

        Route::get('/news/categories/{newsCategory}', [NewsCategoryController::class, 'edit'])
            ->middleware('permission:news_categories,edit')
            ->name('admin.news-categories.edit');

        Route::patch('/news/categories/{newsCategory}', [NewsCategoryController::class, 'update'])
            ->middleware('permission:news_categories,edit')
            ->name('admin.news-categories.update');

        Route::delete('/news/categories/{newsCategory}', [NewsCategoryController::class, 'destroy'])
            ->middleware('permission:news_categories,delete')
            ->name('admin.news-categories.destroy');

        Route::get('/news/{newsArticle}', [AdminNewsArticleController::class, 'edit'])
            ->middleware('permission:news,edit')
            ->name('admin.news-articles.edit');

        Route::patch('/news/{newsArticle}', [AdminNewsArticleController::class, 'update'])
            ->middleware('permission:news,edit')
            ->name('admin.news-articles.update');

        Route::delete('/news/{newsArticle}', [AdminNewsArticleController::class, 'destroy'])
            ->middleware('permission:news,delete')
            ->name('admin.news-articles.destroy');

        Route::get('/news/{newsArticle}/history', [AdminNewsArticleController::class, 'history'])
            ->middleware('permission:news,edit')
            ->name('admin.news-articles.history');

        Route::post('/news/{newsArticle}/revisions/{revision}/restore', [AdminNewsArticleController::class, 'restoreRevision'])
            ->middleware('permission:news,edit')
            ->name('admin.news-articles.revisions.restore');

        Route::get('/menus', [MenuController::class, 'index'])
            ->middleware('permission:menus,view')
            ->name('admin.menus.index');

        Route::get('/menus/{menu}', [MenuController::class, 'edit'])
            ->middleware('permission:menus,edit')
            ->name('admin.menus.edit');

        Route::post('/menus/{menu}/items', [MenuItemController::class, 'store'])
            ->middleware('permission:menus,edit')
            ->name('admin.menu-items.store');

        Route::patch('/menu-items/{menuItem}', [MenuItemController::class, 'update'])
            ->middleware('permission:menus,edit')
            ->name('admin.menu-items.update');

        Route::delete('/menu-items/{menuItem}', [MenuItemController::class, 'destroy'])
            ->middleware('permission:menus,edit')
            ->name('admin.menu-items.destroy');

        Route::post('/menu-items/{menuItem}/move', [MenuItemController::class, 'move'])
            ->middleware('permission:menus,edit')
            ->name('admin.menu-items.move');

        Route::get('/contact-messages', [AdminContactMessageController::class, 'index'])
            ->middleware('permission:contact_messages,view')
            ->name('admin.contact-messages.index');

        Route::get('/contact-messages/{contactMessage}', [AdminContactMessageController::class, 'show'])
            ->middleware('permission:contact_messages,view')
            ->name('admin.contact-messages.show');

        Route::patch('/contact-messages/{contactMessage}/archive', [AdminContactMessageController::class, 'archive'])
            ->middleware('permission:contact_messages,edit')
            ->name('admin.contact-messages.archive');

        Route::get('/admins', [AdminUserController::class, 'index'])
            ->middleware('permission:admins,view')
            ->name('admin.admins.index');

        Route::get('/admins/create', [AdminUserController::class, 'create'])
            ->middleware('permission:admins,create')
            ->name('admin.admins.create');

        Route::post('/admins', [AdminUserController::class, 'store'])
            ->middleware('permission:admins,create')
            ->name('admin.admins.store');

        Route::get('/admins/{admin}/edit', [AdminUserController::class, 'edit'])
            ->middleware('permission:admins,view')
            ->name('admin.admins.edit');

        Route::patch('/admins/{admin}', [AdminUserController::class, 'update'])
            ->middleware('permission:admins,edit')
            ->name('admin.admins.update');

        Route::patch('/admins/{admin}/password', [AdminUserController::class, 'updatePassword'])
            ->middleware('permission:admins,edit')
            ->name('admin.admins.password');

        Route::delete('/admins/{admin}', [AdminUserController::class, 'destroy'])
            ->middleware('permission:admins,delete')
            ->name('admin.admins.destroy');

        Route::patch('/admins/{admin}/status', [AdminUserController::class, 'toggleStatus'])
            ->middleware('permission:admins,edit')
            ->name('admin.admins.toggle-status');

        Route::post('/admins/{admin}/reset-password', [AdminUserController::class, 'resetPassword'])
            ->middleware('permission:admins,edit')
            ->name('admin.admins.reset-password');

        Route::get('/admins/{admin}/permissions', [AdminPermissionController::class, 'edit'])
            ->middleware('permission:admins,edit')
            ->name('admin.admins.permissions.edit');

        Route::patch('/admins/{admin}/permissions', [AdminPermissionController::class, 'update'])
            ->middleware('permission:admins,edit')
            ->name('admin.admins.permissions.update');

    });

});

// Catch-all for admin-created pages (career/support/terms/faq/generic templates
// and any future template), which have no hand-written Blade view. Must stay
// LAST so it never shadows a named route above — those 5 pages keep rendering
// via PageController::show() exactly as before.
Route::get('/{slug}', fn (string $slug) => app(PageController::class)->showDynamic($slug))
    ->where('slug', '[a-z0-9-]+')
    ->name('page.dynamic');