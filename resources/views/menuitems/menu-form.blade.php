<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<x-atrochar-form-section method="{{$method}}" action="{{ $action }}">
    <x-slot name="title">
        <a href="{{ route('atrochar.menus.show', $parent) }}"> {{ $parent->name }}</a>
    </x-slot>

    <x-slot name="description">
        {{ __('Links inside your Menu') }}
    </x-slot>

        <x-slot name="form">
            <!-- Name -->
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input id="name" name="name" type="text" class="mt-1 block w-full"
                    value="{{old('name', isset($menu) ? $menu->name : '' )}}"
                />
                <x-jet-input-error for="name" class="mt-2" />
            </div>

            <!-- Description -->
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="description" value="{{ __('Description') }}" />
                <x-jet-input id="description" name="description" type="text" class="mt-1 block w-full"
                    value="{{old('description', isset($menu) ? $menu->description : '' )}}"   />
                <x-jet-input-error for="description" class="mt-2" />
            </div>

            <!-- href -->
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="href" value="{{ __('Link') }} - Url or Route Name"  />
                <!--<x-jet-input id="href" type="text" class="mt-1 block w-full" name="href"
                    value="{{old('href', isset($menu) ? $menu->href : '' )}}"   />-->
                <input type="text" name="href" list="routes" value="{{old('href', isset($menu) ? $menu->href : '' )}}" class="form-input rounded-md shadow-sm mt-1 block w-full" />
                @if(isset($routes))
                    <datalist id="routes">
                        @foreach ($routes as $route)
                            <option value="{{ $route }}">{{ route($route)}}</option>
                        @endforeach
                    </datalist>
                @endif
                <x-jet-input-error for="href" class="mt-2" />
            </div>

            <!-- icon -->
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="icon" value="{{ __('Icon') }}" />
                <select id="icon" class="mt-1 block w-full" name="icon">
                    @foreach($icons as $groupName => $groupIcons)
                        <optgroup label="{{$groupName}}">
                            @foreach ($groupIcons as $key => $label)
                                <option value="{{ $key }}" {{ ($key == old('icon', isset($menu) ? $menu->icon : '' ) ? 'selected':'') }}>{{ $label }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>

            <!-- permission -->
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="permission" value="{{ __('Permission') }}" />
                <x-jet-input id="permission" type="text" class="mt-1 block w-full" name="permission"
                    value="{{old('permission', isset($menu) ? $menu->permission : '' )}}"   />
                <x-jet-input-error for="href" class="mt-2" />
            </div>

            <!-- href -->
            @if(isset($menu))
                <div class="col-span-6 sm:col-span-4">
                    <x-jet-label for="order" value="{{ __('Order') }}" />
                    <x-jet-input id="order" type="text" class="mt-1 block w-full" name="order"
                        value="{{old('order', isset($menu) ? $menu->order : '' )}}"   />
                    <x-jet-input-error for="href" class="mt-2" />
                </div>
            @endif

            <!-- Checkboxes -->
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="permissions" value="{{ __('Options') }}" />

                <label class="flex items-center">
                    <input type="checkbox" class="form-checkbox" name="newwindow"
                        @if ( old('newwindow', isset($menu) ? $menu->newwindow : false) ) checked @endif>
                    <span class="ml-2 text-sm text-gray-600">Open in new window</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" class="form-checkbox" name="iframe"
                        @if ( old('iframe', isset($menu) ? $menu->iframe : false) ) checked @endif>
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
<script>
    function formatIcon (icon) {
        if(icon.id == null){ return icon.text }
        var $icon = '<span><img style="display:inline" src="' + icon.id + '" /> ' + icon.text + '<span>';
        return  $icon;
    };
    $(document).ready(function() {
        $('#icon').select2({
            templateResult: formatIcon,
            templateSelection: formatIcon,
            escapeMarkup: (markup) => markup,
        });
    });
</script>
