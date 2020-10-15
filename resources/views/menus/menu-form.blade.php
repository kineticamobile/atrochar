<x-atrochar-form-section method="POST" action="{{ route('atrochar.menus.store') }}">
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
        </x-slot>

        <x-slot name="actions">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                Save
            </button>
        </x-slot>

</x-atrochar-form-section>
