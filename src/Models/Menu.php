<?php

namespace Kineticamobile\Atrochar\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kineticamobile\Atrochar\Facades\Atrochar;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'href',
        'icon',
        'menu_id',
        'newwindow',
        'iframe',
        'permission',
        'order'
    ];

    public static function whole($menuName)
    {
        return Menu::with('menus')
                ->where('menu_id', null)
                ->where('name', $menuName)
                ->first()
        ;
    }

    public function menus()
    {
        return $this->hasMany(self::class)->orderBy('order');
    }

    public function menu()
    {
        return $this->belongsTo(self::class);
    }

    public function breadcrumbs()
    {
        $breadcrumbs = $this->name;
        $menu = $this->menu;
        while($menu != null)
        {
            $breadcrumbs = $menu->name . " >> " .$breadcrumbs;
            $menu = $menu->menu;
        }
        return $breadcrumbs;
    }

    public function url()
    {
        return Atrochar::getRouteNames()->contains($this->href) ?
            route($this->href) :
            $this->href;
    }

    protected static function newFactory()
    {
        return \Kineticamobile\Atrochar\Factories\MenuFactory::new();
    }

    public static function check($menu)
    {
        if($menu instanceof Menu) {
            return true;
        }

        if(is_integer($menu) && Menu::find($menu) != null) {
            return true;
        }

        if(is_string($menu) && Menu::whole($menu) != null) {
            return true;
        }

        return false;
    }

    public function href(){
        $namedRoutes = Atrochar::getRouteNames();
        $href = $this->iframe ?
                    route("atrochar.menus.iframe", $this) :(
                $namedRoutes->contains($this->href) ?
                    route($this->href) :(

                $this->href ));
        return $href;
    }

    public function activeClass(){
        return request()->url() === $this->href ? 'active' : '';
    }

    public function target(){
        return $this->newwindow ? " target='_blank' ": "";
    }

    public function icon(){
        return $this->icon != "" ? "<i class='fas fa-{$this->icon}'></i>" : "";
    }

    public function notAllow($user){
        return $this->permission != "" && !$user->canViewMenuItem($this->permission);
    }

    public function link(){
        return "<a href='{$this->href()}' {$this->target()} class='{$this->activeClass()} '>{$this->icon()}{$this->name}</a>";
    }

}
