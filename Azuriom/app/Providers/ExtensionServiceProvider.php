<?php

namespace Azuriom\Providers;

use Azuriom\Extensions\Plugin\PluginManager as Plugins;
use Azuriom\Extensions\Theme\ThemeManager as Themes;
use Illuminate\Support\ServiceProvider;

class ExtensionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function register(): void
    {
        $this->app->singleton(Plugins::class);
        $this->app->alias(Plugins::class, 'plugins');

        $this->app->singleton(Themes::class);
        $this->app->alias(Themes::class, 'themes');

        $this->app->make(Plugins::class)->loadPlugins($this->app, ! is_installed());
    }

    /**
     * Bootstrap services.
     */
    public function boot(Themes $themes): void
    {
        $theme = setting('theme');

        if ($theme === null || ! is_installed()) {
            return;
        }

        $themes->loadTheme($theme);

        $themeLangPath = $themes->path('lang', $theme);

        if (is_dir($themeLangPath)) {
            $this->loadTranslationsFrom($themeLangPath, 'theme');
        }
    }
}
