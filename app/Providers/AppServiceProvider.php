<?php

namespace App\Providers;

use App\Models\BlogPost;
use App\Models\Menu;
use App\Models\NewsArticle;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
            $view->with('topbarMenu', $menus['topbarMenu']);
        });

        $this->app->terminating(function () {
            $this->app->forgetInstance('cms.theme-settings');
            $this->app->forgetInstance('cms.menus');
        });

        // Public form submissions (Contact, Appointment, Product Inquiry):
        // two independent limits, both enforced. The per-IP limit catches
        // rapid-fire bot bursts; the per-email limit exists for a different
        // reason — every submission emails a confirmation to the address in
        // the "email" field, so without this an attacker could submit the
        // form repeatedly with a victim's address to flood their inbox
        // (an "email bomb"), while easily staying under any per-IP limit by
        // rotating IPs/proxies. Limiting by the submitted email closes that
        // off regardless of how many IPs are used.
        RateLimiter::for('public-form', function (Request $request) {
            return [
                Limit::perMinute(5)->by('form-ip:'.$request->ip()),
                Limit::perHour(3)->by('form-email:'.strtolower((string) $request->input('email'))),
            ];
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
                'items' => fn ($q) => $q->where('is_active', true)->with([
                    'page',
                    'activeChildren' => fn ($q2) => $q2->with('page'),
                ]),
            ])->whereIn('key', ['header', 'footer', 'topbar'])->get()->keyBy('key');

            return [
                'headerMenu' => $menus->get('header'),
                'footerMenu' => $menus->get('footer'),
                'topbarMenu' => $menus->get('topbar'),
            ];
        } catch (\Throwable $e) {
            return [
                'headerMenu' => null,
                'footerMenu' => null,
                'topbarMenu' => null,
            ];
        }
    }
}
