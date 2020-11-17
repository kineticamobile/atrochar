<ul>
@isset($menuToShow)
    @foreach ($menuToShow->menus as $menu)
        @php
            // Dont show item if user havent permissions
            if($menu->notAllow(request()->user())) { continue; }
        @endphp
        <li>
            <a href='{{ $menu->href() }}' {!! $menu->target() !!} class='{{ $menu->activeClass() }}'>
                @if ($menu->icon() != "")
                    <img style="display:inline" src="{{ $menu->icon() }}" />
                @endif
                {{ $menu->name }}
            </a>
            <!-- Submenus -->
            @if($menu->menus->count() > 0)
                <div>
                    @foreach ($menu->menus as $subitemMenu)
                        @php if($menu->notAllow(request()->user())) { continue; } @endphp

                        <a href='{{ $subitemMenu->href() }}' {!! $subitemMenu->target() !!} class='{{ $subitemMenu->activeClass() }}'>
                            @if ($subitemMenu->icon() != "")
                                <img style="display:inline" src="{{ $subitemMenu->icon() }}" />
                            @endif
                            {{ $subitemMenu->name }}
                        </a>

                    @endforeach
                </div>
            @endif
            <!-- End Submenus -->
        </li>
    @endforeach
@endisset
</ul>

