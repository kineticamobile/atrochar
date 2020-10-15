<x-atrochar-index-section>

<x-slot name="title">
        {{ $title }}
    </x-slot>

    <x-slot name="description">
        {{ __('Links in your menu.') }}
    </x-slot>

        <x-slot name="content">
            <div class="space-y-6">
                @foreach ($items as $item)
                <div class="flex items-center justify-between">
                    <div>
                        <a href="{{ route("atrochar.menuitems.show", $item)}}"> {{ $item->name }}</a>
                    </div>

                    <div class="flex items-center">


                        <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none" >
                            {{ __('Delete') }}
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </x-slot>

        <x-slot name="actions">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                <a href="{{ route("atrochar.menuitems.create", ["parent" => $menu->id]) }}"> Create</a>
            </button>
        </x-slot>
    </x-atrochar-index-section>

