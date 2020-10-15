<x-atrochar-form-section method="POST" action="{{route('atrochar.menuitems.store', ['parent' => $menu->id])}}">
    <x-slot name="title">
        {{ __('Menu') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Create Menu Where you can add items and render menu in your views.') }}
    </x-slot>

        <x-slot name="form">
            <!-- Name -->
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input id="name" type="text" class="mt-1 block w-full" name="name" />
                <x-jet-input-error for="name" class="mt-2" />
            </div>

            <!-- Description -->
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="description" value="{{ __('Description') }}" />
                <x-jet-input id="description" type="text" class="mt-1 block w-full" name="description" />
                <x-jet-input-error for="description" class="mt-2" />
            </div>

            <!-- href -->
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="href" value="{{ __('Link') }}" />
                <x-jet-input id="href" type="text" class="mt-1 block w-full" name="href" />
                <x-jet-input-error for="href" class="mt-2" />
            </div>

            <!-- Name -->
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="permissions" value="{{ __('Options') }}" />

                <label class="flex items-center">
                    <input type="checkbox" class="form-checkbox" name="newwindow">
                    <span class="ml-2 text-sm text-gray-600">Open in new window</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" class="form-checkbox" name="iframe">
                    <span class="ml-2 text-sm text-gray-600">Open in iframe</span>
                </label>

            </div>

        </x-slot>

        <x-slot name="actions">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                Save
            </button>
        </x-slot>

</x-atrochar-form-section>
