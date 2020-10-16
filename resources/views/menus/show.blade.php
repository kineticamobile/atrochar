<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Menus') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @include('atrochar::menus.menu-show', ["title"=> $menu->breadcrumbs(), "items" => $menus])
        </div>
    </div>
</x-app-layout>
