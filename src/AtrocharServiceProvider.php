<?php

namespace Kineticamobile\Atrochar;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Kineticamobile\Atrochar\Models\Menu;

class AtrocharServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'kineticamobile');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'kineticamobile');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->registerBladeDirectives();

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/atrochar.php', 'atrochar');

        // Register the service the package provides.
        $this->app->singleton('atrochar', function ($app) {
            return new Atrochar;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['atrochar'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/atrochar.php' => config_path('atrochar.php'),
        ], 'atrochar.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/kineticamobile'),
        ], 'atrochar.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/kineticamobile'),
        ], 'atrochar.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/kineticamobile'),
        ], 'atrochar.views');*/

        // Registering package commands.
        // $this->commands([]);
    }

    protected function registerBladeDirectives()
    {
        Blade::directive('atrochar', function ($menuName) {
            $menuName = trim($menuName, "'\"");
            $menuToShow = Menu::whole($menuName);
            $namedRoutes = collect(Route::getRoutes())->map(function($route) {
                return $route->getName();
            })->filter();

            $lis = [];

            foreach($menuToShow->menus as $menu){
                $href = $namedRoutes->contains($menu->href) ? route($menu->href):$menu->href;
                $lis[]= "<li>"
                    . "<a href='$href'>"
                        . $menu->name
                    . "</a>"
                . "</li>";
            }

            return "<ul>" . implode($lis) . "</ul>";

            /*
            Blade::compileString(
                  '@can(\'manage users\') '
                    . '<div class="block px-4 py-2 text-xs text-gray-400">Lumki</div>'

                    . '<a href="{{ route(\'lumki.index\') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">'
                        . '{{ __(\'Users\') }}'
                    . '</a>'
                    . '<a href="{{ route(\'lumki.roles.index\') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">'
                        . '{{ __(\'Roles\') }}'
                    . '</a>'
                    . '<a href="{{ route(\'lumki.permissions.index\') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">'
                        . '{{ __(\'Permissions\') }}'
                    . '</a>'
                . ' @endcan '
                . '@impersonating'
                    . '<div class="border-t border-gray-100"></div>'
                    . '<a href="{{ route(\'impersonate.leave\') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">'
                        . '{{ __(\'Leave impersonation\') }}'
                    . '</a>'
                . '@endImpersonating'

            );
            */
        });
    }
}
