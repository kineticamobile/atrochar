<?php

namespace Kineticamobile\Atrochar;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Kineticamobile\Atrochar\Atrochar;
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
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'atrochar');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->registerBladeDirectives();

        $this->app->bind("atrochar", function(){
            return new Atrochar;
        });

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        };

        //Blade::component('atrochar', 'components/form-section');
        Blade::component('atrochar::components.form-section', 'atrochar-form-section');
        Blade::component('atrochar::components.index-section', 'atrochar-index-section');

        Collection::macro('reorder', function () {
            $orderNumber = 1;
            foreach($this->items as $item)
            {
                if( $item instanceof Menu ){
                    $item->order = $orderNumber;
                    $orderNumber++;
                }
            }
            return new static($this->items);
        });

        Collection::macro('move', function ($from, $to) {
            // https://stackoverflow.com/questions/12624153/move-an-array-element-to-a-new-index-in-php
            $output = $this->items;
            array_splice($output, $to, 0, array_splice($output,$from,1));
            return new static($output);
        });

        Collection::macro('moveAndReorder', function ($from, $to) {
            return new static($this->move($from, $to)->reorder());
        });
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
        Blade::directive('menu', function ($slot) {
            return "<?php echo Atrochar::generateMenu($slot) ?>";
        });
        Blade::if('menu_exists', function ($menu) {
            return Atrochar::exists($menu);
        });
    }
}
