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

        if($menuToShow == null){
            return "Menu Not Found!";
        }
        $namedRoutes = self::getRouteNames();
        //dd($menuName, $menuToShow);
        $lis = [];
        $class = isset($options['class']) ? " class='". $options['class'] ."' ": "";
        $linkTag = isset($options['linkTag']) ?  $options['linkTag'] : "a";

        $listStartTag = isset($options['listStartTag']) ?  $options['listStartTag'] : "<ul>";
        $listEndTag = isset($options['listEndTag']) ?  $options['listEndTag'] : "</ul>";

        $itemStartTag = isset($options['itemStartTag']) ?  $options['itemStartTag'] : "<li>";
        $itemEndTag = isset($options['itemEndTag']) ?  $options['itemEndTag'] : "</li>";


        foreach($menuToShow->menus as $menu){
            $href = $menu->iframe ?
                        route("atrochar.menus.iframe", $menu) :(
                    $namedRoutes->contains($menu->href) ?
                        route($menu->href) :(

                        $menu->href ));
            //dd(request()->url(), $href);
            $active = request()->url() == $href ? "true" : "false";
            $target = $menu->newwindow ? " target='_blank' ": "";
            $lis[]=
            $itemStartTag
                . "<$linkTag href='$href' $target $class :active='$active'>"
                    . $menu->name
                . "</$linkTag>"
            . $itemEndTag;
        }

        return $listStartTag . implode($lis) . $listEndTag;
    }

}
