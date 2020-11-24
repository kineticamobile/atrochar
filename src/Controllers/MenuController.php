<?php

namespace Kineticamobile\Atrochar\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kineticamobile\Atrochar\Models\Menu;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("atrochar::menus.index", [
            "menus" => Menu::where('menu_id', null)->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("atrochar::menus.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedAttributes = request()->validate(["name" => "required"]);

        $validatedAttributes["description"] = request('description') ?? "";

        Menu::create($validatedAttributes);

        return redirect(route("atrochar.menus.index"));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        return view("atrochar::menus.show", [
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
        return view("atrochar::menus.edit", compact('menu'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu)
    {
        $validatedAttributes = request()->validate(["name" => "required"]);

        $validatedAttributes["description"] = request('description') ?? "";

        $menu->update($validatedAttributes);
        return redirect()->route("atrochar.menus.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {
        $menu->menus()->delete();
        $menu->delete();
        return redirect()->route("atrochar.menus.index");
    }
}
