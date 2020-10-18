<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Menus') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            Object - Comprueba si es clase Menu - @@menu($menu)<hr>
            @menu($menu)
            <br/><br/>
            Cadena - Busca por nombre - @@menu($menu->name) o @@menu('{{$menu->name}}')<hr>
            @menu($menu->name)
            <br/><br/>
            Id - Busca por Id- @@menu($menu->id) o @@menu({{$menu->id}})<hr>
            @menu($menu->id)
            <br/><br/>
            Invalid Parameter - @@menu($menu->description)<hr>
            @menu($menu->description)
            <br/><br/>
            Object - Using Theme- @@menu($menu, "jetstream")<hr>
            @menu($menu, "jetstream")
            <br/><br/>
            Object - Using Options- @@menu($menu, ["class" => "bg-gray-500 font-mono"])<hr>
            @menu($menu, ["class" => "bg-gray-500 font-mono"])
        </div>
    </div>
</x-app-layout>
