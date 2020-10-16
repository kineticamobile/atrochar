<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Menus') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <iframe width="100%" height="600px" src="{{ $menu->url() }}" title="{{ $menu->name }}">
            </iframe>
        </div>
    </div>
</x-app-layout>
