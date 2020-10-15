<?php

namespace Kineticamobile\Atrochar\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
            "menu" => Menu::find(request('parent'))
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
            "description" => "required",
            "href" => "required",
            //"newwindow" => "required",
            "parent" => "required"
        ]);

        $validatedAttributes["menu_id"] = request("parent");
        $validatedAttributes["order"] = 1 + Menu::where('menu_id', request("parent"))->count();
        $validatedAttributes['icon'] = request('icon' ,'');
        $validatedAttributes['newwindow'] = request('newwindow', 0);
        $validatedAttributes['iframe'] = request('iframe', 0);

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
        return view("atrochar::menus.index", [
            "menu" => $menu,
            "menus" => $menu->menus
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        //
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
            "description" => "required",
            "href" => "required",
            "newwindow" => "required",
            "order" => "required"
        ]);

        $validatedAttributes['icon'] = request('icon');

        $menuitem->order = $validatedAttributes["order"];

        if( $menuitem->isDirty('order') ){
            $from = $menuitem->getOriginal('order') - 1;
            $to = $menuitem->order - 1;
            $to = $to < 0 ? 0 : $to;
            $collection = Menu::with('menus')
                    ->find($menuitem->menu_id)
                    ->menus
                    ->moveAndReorder($from, $to, $menuitem)
                    ->map(fn($menu) => $menu->save())
            ;
            //dd($collection);
        }

        $validatedAttributes['icon'] = request('icon');
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
