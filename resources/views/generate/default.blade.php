<ul>
@isset($menuToShow)
    @foreach ($menuToShow->menus as $menu)
        @php
            // Dont show item if user havent permissions
            if($menu->notAllow(request()->user())) { continue; }
        @endphp
        <li>
            <a href='{{ $menu->href() }}' {!! $menu->target() !!} class='{{ $menu->activeClass() }}'>
                {!! $menu->icon() !!}
                {{ $menu->name }}
            </a>
            @if($menu->menus->count() > 0)
                <div>
                    @foreach ($menu->menus as $subitemMenu)
                        @php if($menu->notAllow(request()->user())) { continue; } @endphp

                        <a href='{{ $subitemMenu->href() }}' {!! $subitemMenu->target() !!} class='{{ $subitemMenu->activeClass() }}'>
                            {!! $subitemMenu->icon() !!}
                            {{ $subitemMenu->name }}
                        </a>

                    @endforeach
                </div>
            @endif
        </li>
    @endforeach
@endisset
</ul>

