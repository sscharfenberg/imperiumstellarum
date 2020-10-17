<nav class="drawer{{ Auth::user() && Auth::user()->drawer_open ? ' open' : '' }}">
    <aside class="time-data">
        <ul>
            <li>@lang("app.drawer.localTime")</li>
            <li>
                <time datetime="formattedTime" id="localTime"></time>
            </li>
        </ul>
    </aside>
    @if(Auth::user() && count(Auth::user()->players) > 0)
        <aside class="game-data">
            <ul>
                <li>@lang("app.drawer.currentTurn")</li>
                <li class="turn">
                    <x-icon name="turn" size="2" />
                    892
                </li>
            </ul>
            <ul>
                <li>
                    @lang("app.drawer.nextTurn")<br />
                    ------------------- (tbd!)
                </li>
                <li><time datetime="00:05:25">00:05:25</time></li>
            </ul>
        </aside>
    @endif
    <ul class="drawer-list">
        @if(Auth::user() && count(Auth::user()->players) > 0)
            <li class="drawer-list__item">
                <a href="/game/1/empire" class="drawer-list__link">
                     <x-icon name="empire" size="2" /> @lang("game.empire.navTitle")
                </a>
            </li>
            <li class="drawer-list__item">
                <a href="/game/1/shipyards" class="drawer-list__link">
                     <x-icon name="shipyards" size="2" /> @lang("game.shipyards.navTitle")
                </a>
            </li>
            <li class="drawer-list__item">
                <a href="/game/1/fleets" class="drawer-list__link">
                     <x-icon name="fleets" size="2" /> @lang("game.fleets.navTitle")
                </a>
            </li>
            <li class="drawer-list__item">
                <a href="/game/1/research" class="drawer-list__link">
                     <x-icon name="research" size="2" /> @lang("game.research.navTitle")
                </a>
            </li>
            <li class="drawer-list__item">
                <a href="/game/1/starmap" class="drawer-list__link">
                     <x-icon name="starmap" size="2" /> @lang("game.starmap.navTitle")
                </a>
            </li>
            <li class="drawer-list__item drawer-list__item--both-borders">
                <a href="/game/1/galnet" class="drawer-list__link">
                     <x-icon name="galnet" size="2" /> @lang("game.diplomacy.navTitle")
                </a>
            </li>
        @endif
        <li class="drawer-list__item drawer-list__item--margin">
            <a href="https://discuss.imperiumstellarum.io" class="drawer-list__link">
                <x-icon name="chat" size="2" /> @lang('app.drawer.forums')
            </a>
        </li>
        <li class="drawer-list__item">
            <a href="https://github.com/sscharfenberg/imperiumstellarum" class="drawer-list__link">
                <x-icon name="save" size="2" /> @lang('app.drawer.github')
            </a>
        </li>
        <li class="drawer-list__item">
            <a href="https://www.mantisbt.org/" class="drawer-list__link">
                <x-icon name="bug" size="2" /> @lang('app.drawer.bugs')
            </a>
        </li>
        <li class="drawer-list__item">
            <x-switch ref-id="themeToggle" is-checked="1" label-on="D" label-off="L" data="theme-toggle"/>
        </li>
    </ul>
</nav>
