<?php

namespace Kineticamobile\Atrochar;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Kineticamobile\Atrochar\Models\Menu;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Atrochar
{
    public static function getIcons($path = "")
    {
        $path = public_path( config("atrochar.iconsPath") );
        if(!is_dir($path)){
            return [];
        }
        $allowedExtensions = collect(config("atrochar.iconsAllowedExtension"));
        $publicPath = public_path();
        $icons = [];
        $files = new RecursiveDirectoryIterator($path);
        foreach (new RecursiveIteratorIterator($files) as $file) {
            if ($file->isDir() || !$allowedExtensions->contains($file->getExtension()) ) {
                continue;
            }
            $relatedUrlPath = str_replace($publicPath, "", $file->getPathName());
            $icons[$file->getPathInfo()->getFileName()][$relatedUrlPath] = $file->getBasename('.' . $file->getExtension());
        }
        return $icons;
    }

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

    public static function exists($menu)
    {
        return Menu::check($menu);
    }

    public static function generateMenuView($menu, $view = "default"){
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

        return view("atrochar::generate.$view", ["menuToShow" => $menuToShow]);
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

            $submenus = $menu->menus->count() > 0 ? self::generateMenu($menu, $definitiveOptions["submenusOptions"]):'';


            $selectedClass = request()->url() === $href ? $activeClass : $class;
            $target = $menu->newwindow ? " target='_blank' ": "";
            $icon = $menu->icon != "" ? "<i class='fas fa-{$menu->icon}'></i>" : "";
            $lis[]=
                $itemStartTag
                    . "<$linkTag href='$href' $target class='$selectedClass'>"
                        . $icon
                        . $menu->name
                    . "</$linkTag>"
                    . $submenus
                . $itemEndTag
            ;
        }

        return $listStartTag . implode($lis) . $listEndTag;
    }

}
