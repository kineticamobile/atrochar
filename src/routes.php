<?php

use Illuminate\Support\Facades\Route;
use Kineticamobile\Atrochar\Facades\Atrochar;
use Kineticamobile\Atrochar\Middleware\ManageMenuMiddleware;
use Kineticamobile\Atrochar\Models\Menu;

$prefix = config('atrochar.prefix') ?? "atrochar";
$iframe = config('atrochar.iframe') ?? "i";
$middleware = config('atrochar.middleware') ?? 'auth:sanctum';
Route::namespace("Kineticamobile\Atrochar\Controllers")
    ->prefix($prefix) //  Url
    ->as("atrochar.") // Name of routes
    ->middleware(['web',$middleware, ManageMenuMiddleware::class])
    ->group(function () use ($iframe){

        Route::resource('menus', 'MenuController');
        Route::resource('menuitems', 'MenuItemController');
        Route::get('menus/ul/{menu}', function(Menu $menu){
            return view("atrochar::menus.ul", [
                "menu" => $menu
            ]);
        })->name("menus.ul");

        Route::get($iframe.'/{menu}', function(Menu $menu){
            return view("atrochar::menus.iframe", [
                "menu" => $menu
            ]);
        })->name("menus.iframe");

        Route::get('test', function() {
            //dd(Atrochar::generateMenu("Prueba"));
            dd(Atrochar::checkConfig());
        });
});
