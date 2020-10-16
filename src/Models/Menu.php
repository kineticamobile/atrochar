<?php

namespace Kineticamobile\Atrochar\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    protected static function newFactory()
    {
        return \Kineticamobile\Atrochar\Factories\MenuFactory::new();
    }
}
