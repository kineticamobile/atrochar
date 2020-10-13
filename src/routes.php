<?php

use Illuminate\Support\Facades\Route;

Route::namespace("Kineticamobile\Atrochar\Controllers")
    ->prefix('atrochar') //  Url
    ->as('atrochar.') // Name of routes
    ->middleware(['web','auth:sanctum'])
    ->group(function () {

        Route::resource('menus', 'MenuController');
        Route::resource('menuitems', 'MenuItemController');

});
