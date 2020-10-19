<?php

use Illuminate\Support\Facades\Route;
use Kineticamobile\Atrochar\Facades\Atrochar;
use Kineticamobile\Atrochar\Middleware\ManageMenuMiddleware;
use Kineticamobile\Atrochar\Models\Menu;

Route::namespace("Kineticamobile\Atrochar\Controllers")
    ->prefix('atrochar') //  Url
    ->as('atrochar.') // Name of routes
    ->middleware(['web','auth:sanctum', ManageMenuMiddleware::class])
    ->group(function () {

        Route::resource('menus', 'MenuController');
        Route::resource('menuitems', 'MenuItemController');
        Route::get('menus/ul/{menu}', function(Menu $menu){
            return view("atrochar::menus.ul", [
                "menu" => $menu
            ]);
        })->name("menus.ul");

        Route::get('i/{menu}', function(Menu $menu){
            return view("atrochar::menus.iframe", [
                "menu" => $menu
            ]);
        })->name("menus.iframe");

        Route::get('test', function() {
            //dd(Atrochar::generateMenu("Prueba"));
            dd(Atrochar::checkConfig());
        });
});
