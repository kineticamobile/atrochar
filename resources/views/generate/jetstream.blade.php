@isset($menuToShow)
    @foreach ($menuToShow->menus as $menu)
        @php
            // Dont show item if user havent permissions
            if($menu->notAllow(request()->user())) { continue; }
        @endphp
        <a href='{{ $menu->href() }}' {{$menu->target()}} class='inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out'>
            <img style="display:inline" src="{{ $menu->icon() }}" />
            {{ $menu->name }}
        </a>
        @if($menu->menus->count() > 0)
            <div>
                @foreach ($menu->menus as $subitemMenu)
                    @php if($menu->notAllow(request()->user())) { continue; } @endphp
                    <a href='{{ $subitemMenu->href() }}' {{$subitemMenu->target()}} class='inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out'>
                        <img style="display:inline" src="{{ $subitemMenu->icon() }}" />
                        {{ $subitemMenu->name }}
                    </a>
                @endforeach
            </div>
        @endif
    @endforeach
@endisset
