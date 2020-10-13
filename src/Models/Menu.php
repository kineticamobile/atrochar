<?php

namespace Kineticamobile\Atrochar\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'href', 'menu_id', 'newwindow', 'order'];

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

    protected static function newFactory()
    {
        return \Kineticamobile\Atrochar\Factories\MenuFactory::new();
    }
}
