<nav class="drawer">
    <aside class="time-data">
        <ul>
            <li>@lang("app.drawer.localTime")</li>
            <li>
                <time datetime="formattedTime" id="localTime"></time>
            </li>
        </ul>
    </aside>
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
    <ul class="drawer-list">
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
        <li class="drawer-list__item">
            <a href="/game/1/galnet" class="drawer-list__link">
                 <x-icon name="galnet" size="2" /> @lang("game.galnet.navTitle")
            </a>
        </li>
        <li class="drawer-list__item">
            <x-switch ref-id="themeToggle" is-checked="1" label-on="D" label-off="L" data="theme-toggle"/>
        </li>
    </ul>
</nav>
