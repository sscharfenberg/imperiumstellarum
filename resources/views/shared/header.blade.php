<ul class="header">
    <li class="header__item">
        <button id="toggleDrawerBtn" :aria-label="@lang("app.header.drawerButton")">
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
        <li class="header__item">
            <button class="header__link both has-submenu">
                <x-icon name="games" size="2" />
                <span>g1</span>
            </button>
            <ul class="submenu left">
                <li class="submenu__item">
                    <a class="active" href="/game/1">g1</a>
                </li>
                <li class="submenu__item">
                    <a href="/game/2">g2</a>
                </li>
            </ul>
        </li>
    @endauth
    @guest
        <li class="header__item">
            <a class="header__link both" href="{{ route('login') }}">
                <x-icon name="key" size="2" />
                <span>@lang("app.login.navTitle")</span>
            </a>
        </li>
        <li class="header__item">
            <a class="header__link both" href="{{ route('register') }}">
                <x-icon name="lightbulb" size="2" />
                <span>@lang("app.register.navTitle")</span>
            </a>
        </li>
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

