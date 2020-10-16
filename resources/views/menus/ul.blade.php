<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Menus') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            Nombre - @@menu($menu->name)<hr>
            @menu($menu->name, [
                "class" => "name"
            ])
            <br/><br/>
            Objeto - @@menu($menu)<hr>
            @menu($menu, [
                "class" => "class"
            ])
            <br/><br/>
            Id - @@menu($menu->id)<hr>
            @menu($menu->id, [
                "class" => "id"
            ])
            <br/><br/>
            Invalid Parameter - @@menu($menu->description)<hr>
            @menu($menu->description, [
                "class" => "not found"
            ])
        </div>
    </div>
</x-app-layout>
