<nav class="drawer{{ Auth::user() && Auth::user()->drawer_open ? ' open' : '' }}">

    <aside class="time-data">
        <ul>
            <li>@lang("app.drawer.localTime")</li>
            <li>
                <time datetime="formattedTime" id="localTime"></time>
            </li>
        </ul>
    </aside>

    @if(Auth::user() && Auth::user()->selectedGame() && count(Auth::user()->selectedGame()->turns) > 0 && $inGame)
        <div id="gameDataTeleportTarget"></div>
    @endif

    @if(Auth::user() && Auth::user()->selectedGame() && count(Auth::user()->selectedGame()->turns) > 0)
        @if(!$inGame)
            <ul class="drawer-list">
                <li class="drawer-list__item">
                    <a href="/game/{{ Auth::user()->selectedGame()->id }}/empire" class="drawer-list__link{{ Request::is('*/empire') ? " active" : "" }}">
                        <x-icon name="empire" size="2" /> @lang("game.empire.navTitle")
                    </a>
                </li>
                <li class="drawer-list__item">
                    <a href="/game/{{ Auth::user()->selectedGame()->id }}/shipyards" class="drawer-list__link{{ Request::is('*/shipyards') ? " active" : "" }}">
                        <x-icon name="shipyards" size="2" /> @lang("game.shipyards.navTitle")
                    </a>
                </li>
                <li class="drawer-list__item">
                    <a href="/game/{{ Auth::user()->selectedGame()->id }}/fleets" class="drawer-list__link{{ Request::is('*/fleets') ? " active" : "" }}">
                        <x-icon name="fleets" size="2" /> @lang("game.fleets.navTitle")
                    </a>
                </li>
                <li class="drawer-list__item">
                    <a href="/game/{{ Auth::user()->selectedGame()->id }}/research" class="drawer-list__link{{ Request::is('*/research') ? " active" : "" }}">
                        <x-icon name="research" size="2" /> @lang("game.research.navTitle")
                    </a>
                </li>
                <li class="drawer-list__item">
                    <a href="/game/{{ Auth::user()->selectedGame()->id }}/starmap" class="drawer-list__link{{ Request::is('*/starmap') ? " active" : "" }}">
                        <x-icon name="starmap" size="2" /> @lang("game.starmap.navTitle")
                    </a>
                </li>
                <li class="drawer-list__item drawer-list__item--both-borders">
                    <a href="/game/{{ Auth::user()->selectedGame()->id }}/diplomacy" class="drawer-list__link{{ Request::is('*/diplomacy') ? " active" : "" }}">
                        <x-icon name="diplomacy" size="2" /> @lang("game.diplomacy.navTitle")
                    </a>
                </li>
            </ul>
        @else
            <div id="drawerTeleportTarget"></div>
        @endif
    @endif

    <ul class="drawer-list drawer-list--bottom">
        <li class="drawer-list__item">
            <a href="https://discuss.imperiumstellarum.io" class="drawer-list__link" rel="noopener">
                <x-icon name="chat" size="2" /> @lang('app.drawer.forums')
            </a>
        </li>
        <li class="drawer-list__item">
            <a href="https://github.com/sscharfenberg/imperiumstellarum" class="drawer-list__link" rel="noopener">
                <x-icon name="save" size="2" /> @lang('app.drawer.github')
            </a>
        </li>
        <li class="drawer-list__item">
            <a href="https://www.mantisbt.org/" class="drawer-list__link" rel="noopener">
                <x-icon name="bug" size="2" /> @lang('app.drawer.bugs')
            </a>
        </li>
        <li class="drawer-list__item">
            <x-switch ref-id="themeToggle" is-checked="1" label-on="D" label-off="L" data="theme-toggle"/>
        </li>
    </ul>

</nav>
