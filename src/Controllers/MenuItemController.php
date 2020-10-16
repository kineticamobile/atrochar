<?php

namespace Kineticamobile\Atrochar\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kineticamobile\Atrochar\Atrochar;
use Kineticamobile\Atrochar\Models\Menu;
use Symfony\Component\VarDumper\VarDumper;

class MenuItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        request()->validate(["parent" => "required"]);

        return view("atrochar::menuitems.create", [
            "parent" => Menu::find(request('parent')),
            "routes" => Atrochar::getRouteNames()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedAttributes = request()->validate([
            "name" => "required",
            "href" => "required",
            "parent" => "required"
        ]);

        $validatedAttributes['description'] = request('description', "");
        $validatedAttributes['icon'] = request('icon' ,'');
        $validatedAttributes['newwindow'] = request('newwindow') ? true:false;
        $validatedAttributes['iframe'] = request('iframe') ? true:false;
        $validatedAttributes['permission'] = request('permission', '');

        $validatedAttributes['menu_id'] = request("parent");
        $validatedAttributes['order'] = 1 + Menu::where('menu_id', request("parent"))->count();

        Menu::create($validatedAttributes);

        return redirect(route("atrochar.menus.show", request("parent")));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        /*
        return view("atrochar::menuitems.index", [
            "menu" => $menu,
            "menus" => $menu->menus
        ]);
        */
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menuitem)
    {
        return view("atrochar::menuitems.edit", [
            "parent" => $menuitem->menu,
            "menu" => $menuitem,
            "routes" => Atrochar::getRouteNames()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menuitem)
    {

        $validatedAttributes = request()->validate([
            "name" => "required",
            "href" => "required",
            "order" => "required"
        ]);

        $validatedAttributes['description'] = request('description', "");
        $validatedAttributes['icon'] = request('icon' , '');
        $validatedAttributes['newwindow'] = request('newwindow') ? true:false;
        $validatedAttributes['iframe'] = request('iframe') ? true:false;
        $validatedAttributes['permission'] = request('permission', '');

        $menuitem->order = $validatedAttributes["order"];

        /*
        *  If order changed, reorder all the links in the parent menu.
        *    - We're going to use array index to modify so first item index is 0
        *    - In database we use first item index is 1 for users
        *    - So we need to substract 1 to the order of the database to move and reorder
        *    - Check if $to is less than zero to avoid array_splice problems on replacement
        */
        if( $menuitem->isDirty('order') ){
            $from = $menuitem->getOriginal('order') - 1;
            $to = $menuitem->order - 1;
            $to = $to < 0 ? 0 : $to;
            Menu::with('menus')
                    ->find($menuitem->menu_id)
                    ->menus
                    ->moveAndReorder($from, $to, $menuitem)
                    ->map(fn($menu) => $menu->save())
            ;
        }

        // We've changed all the order so we don't want to modify order field
        unset($validatedAttributes['order']);
        $menuitem->refresh();
        $menuitem->fill($validatedAttributes);
        $menuitem->save();

        return redirect(route("atrochar.menus.show", $menuitem->menu_id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
