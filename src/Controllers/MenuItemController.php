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

        return Menu::find(request('parent'));
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
            "newwindow" => "required",
            "parent" => "required"
        ]);

        $validatedAttributes["menu_id"] = request("parent");
        $validatedAttributes["order"] = 1 + Menu::where('menu_id', request("parent"))->count();
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
        return $menu->load('menus');
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
        //dd($menuitem);
        $validatedAttributes = request()->validate([
            "name" => "required",
            "description" => "required",
            "href" => "required",
            "newwindow" => "required",
            "order" => "required"
        ]);


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
