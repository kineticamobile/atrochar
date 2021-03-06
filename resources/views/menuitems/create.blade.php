<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Menus') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @include('atrochar::menuitems.menu-form', [
                "action" => route('atrochar.menuitems.store', ['parent' => $parent->id]),
                "method" => "POST"
            ])
        </div>
    </div>
</x-app-layout>
