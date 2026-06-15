<?php

namespace App\Providers;

use App\Models\Setting;
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
        $this->app->singleton('cms.theme-settings', fn () => $this->loadThemeSettings());

        View::composer('*', function ($view): void {
            $view->with('themeSettings', $this->app->make('cms.theme-settings'));
        });

        $this->app->terminating(function () {
            $this->app->forgetInstance('cms.theme-settings');
        });
    }

    /**
     * Load theme settings keyed by setting key, with graceful fallback
     * before migrations have run (e.g. during initial setup/CI).
     */
    private function loadThemeSettings()
    {
        try {
            return Setting::where('group', 'theme')->with('media')->get()->keyBy('key');
        } catch (\Throwable $e) {
            return collect();
        }
    }
}
