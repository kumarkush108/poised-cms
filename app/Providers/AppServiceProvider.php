<?php

namespace App\Providers;

use App\Models\BlogPost;
use App\Models\Menu;
use App\Models\NewsArticle;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(config_path('cms/templates.php'), 'cms.templates');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Short, stable polymorphic type aliases (content_media.mediable_type,
        // content_revisions.revisionable_type, taggables.taggable_type) instead
        // of full class names — no existing polymorphic relations predate this,
        // so this is purely additive.
        Relation::enforceMorphMap([
            'product' => Product::class,
            'blog_post' => BlogPost::class,
            'news_article' => NewsArticle::class,
        ]);

        $this->app->singleton('cms.theme-settings', fn () => $this->loadThemeSettings());
        $this->app->singleton('cms.menus', fn () => $this->loadMenus());

        View::composer('*', function ($view): void {
            $view->with('themeSettings', $this->app->make('cms.theme-settings'));
            $menus = $this->app->make('cms.menus');
            $view->with('headerMenu', $menus['headerMenu']);
            $view->with('footerMenu', $menus['footerMenu']);
        });

        $this->app->terminating(function () {
            $this->app->forgetInstance('cms.theme-settings');
            $this->app->forgetInstance('cms.menus');
        });
    }

    private function loadThemeSettings()
    {
        // Despite the name (kept for minimal blast radius — this is shared
        // globally as $themeSettings to every view), this loads every
        // settings group, not just 'theme'. Content::settingValue()/
        // settingMediaUrl() are pure key lookups with sensible defaults, so
        // widening this is additive: existing consumers are unaffected, and
        // new consumers (contact info, social links, SEO defaults, etc.) can
        // read from the same already-shared collection.
        try {
            return Setting::with('media')->get()->keyBy('key');
        } catch (\Throwable $e) {
            return collect();
        }
    }

    private function loadMenus(): array
    {
        try {
            $menus = Menu::with([
                'items' => fn ($q) => $q->where('is_active', true)->with('page'),
            ])->whereIn('key', ['header', 'footer'])->get()->keyBy('key');

            return [
                'headerMenu' => $menus->get('header'),
                'footerMenu' => $menus->get('footer'),
            ];
        } catch (\Throwable $e) {
            return [
                'headerMenu' => null,
                'footerMenu' => null,
            ];
        }
    }
}
