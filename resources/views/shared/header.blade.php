<ul class="header">
    <li class="header__item">
        <button
            id="toggleDrawerBtn"
            :aria-label="@lang("app.header.drawerButton")"
            class="{{ Auth::user() && Auth::user()->drawer_open ? 'open' : '' }}">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </li>
    <li class="header__item header__item--left">
        <a href="/" class="header__logo">
            <img src="/assets/images/logo.svg" alt="Imperium Stellarum" />
            <span>Imperium Stellarum</span>
        </a>
    </li>
    @auth
        @if(Auth::user()->isMod())
            <li class="header__item">
                <a class="header__link both" href="{{ route('admin') }}">
                    <x-icon name="build" size="2" />
                    <span>@lang("admin.home.navTitle")</span>
                    @if(App\Models\MessageReport::whereNull('resolved_admin')->count() > 0)
                        <span class="header__link-pip">
                            {{ App\Models\MessageReport::whereNull('resolved_admin')->count() }}
                        </span>
                    @endif
                </a>
            </li>
        @endif
    @endauth
    <li class="header__item">
        <button class="header__link both has-submenu">
            <x-icon name="language" size="2" />
            <span>{{ app()->getLocale() }}</span>
        </button>
        <ul class="submenu left">
            @foreach(config()->get('app.supportedLocales') as $locale)
                <li class="submenu__item">
                    <a href="{{ route('locale', ['locale' => $locale]) }}"@if($locale === app()->getLocale()) class="active"@endif>
                        <img src="/assets/images/flags/{{$locale}}.svg" alt="{{$locale}}" />
                        {{$locale}}
                    </a>
                </li>
            @endforeach
        </ul>
    </li>
    @auth
        @if(count(Auth::user()->players) > 0)
            <li class="header__item">
                <button class="header__link both has-submenu">
                    <x-icon name="games" size="2" />
                    <span>
                        @foreach(Auth::user()->players as $player)
                            @if($player->id === Auth::user()->selected_player)
                                g{{ $player->game->number }}
                            @endif
                        @endforeach
                    </span>
                </button>
                @if(count(Auth::user()->players) > 1)
                    <ul class="submenu left">
                        @foreach(Auth::user()->players as $player)
                            <li class="submenu__item">
                                <a
                                    class="{{ Auth::user()->selected_player === $player->id ? 'active' : '' }}"
                                    href="{{ route('select-game', ['game' => $player->game->id]) }}">
                                    g{{ $player->game->number }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endif
    @endauth
    @guest
        <li class="header__item">
            <a class="header__link both" href="{{ route('login') }}">
                <x-icon name="key" size="2" />
                <span>@lang("app.login.navTitle")</span>
            </a>
        </li>
        @if(config('app.allowRegistration'))
            <li class="header__item">
                <a class="header__link both" href="{{ route('register') }}">
                    <x-icon name="lightbulb" size="2" />
                    <span>@lang("app.register.navTitle")</span>
                </a>
            </li>
        @endif
    @endguest
    @auth
        <li class="header__item">
            <a class="header__link both" href="{{ route('dashboard') }}">
                <x-icon name="user" size="2" />
                <span>@lang("app.dashboard.navTitle")</span>
            </a>
        </li>
        <li class="header__item">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="header__link both">
                    <x-icon name="power" size="2" />
                    <span>@lang("app.logout.navTitle")</span>
                </button>
            </form>
        </li>
    @endauth

</ul>

