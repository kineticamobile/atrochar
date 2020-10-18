<?php

namespace Kineticamobile\Atrochar;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Kineticamobile\Atrochar\Models\Menu;

class Atrochar
{
    public static function getRouteNames()
    {
        return collect(Route::getRoutes())
        ->filter(function ($route) {
            return
                   strpos($route->uri(), "{") === false
                && collect($route->methods())->contains("GET")
            ;
        })->map(function($route) {
            return $route->getName();
        })->filter();
    }

    public static function checkConfig()
    {
        dd(config('atrochar'), config('atrochar.theme.custom'), config('atrochar.theme/jetstream'));
    }

    public static function generateMenu($menu, $options = [])
    {
        $menuToShow =
                ($menu instanceof Menu) ?
                    $menu :(
                is_string($menu) ?
                    Menu::whole($menu) :(
                is_int($menu) ?
                    Menu::find($menu) :(

                    null
        )));

        if($menuToShow == null){ return "Menu Not Found!"; }

        if( is_string($options) ){
            $options = config("atrochar.themes.$options") ?? [];
        }

        $definitiveOptions = array_merge(config("atrochar.defaultTheme"),$options);

        extract($definitiveOptions);

        $lis = [];
        $user = request()->user();
        $namedRoutes = self::getRouteNames();
        foreach($menuToShow->menus as $menu){
            if($menu->permission != "" && !$user->canViewMenuItem($menu->permission))
            {
                continue;
            }
            $href = $menu->iframe ?
                        route("atrochar.menus.iframe", $menu) :(
                    $namedRoutes->contains($menu->href) ?
                        route($menu->href) :(

                        $menu->href ));

            $selectedClass = request()->url() === $href ? $activeClass : $class;
            $target = $menu->newwindow ? " target='_blank' ": "";
            $lis[]=
            $itemStartTag
                . "<$linkTag href='$href' $target class='$selectedClass'>"
                    . $menu->name
                . "</$linkTag>"
            . $itemEndTag;
        }

        return $listStartTag . implode($lis) . $listEndTag;
    }

}
