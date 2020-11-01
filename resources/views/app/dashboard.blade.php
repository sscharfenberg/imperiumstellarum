@extends('layouts.app')

@section('content')

    <header class="page-head">
        <nav class="page-head__breadcrumbs">
            <a class="page-head__crumb" href="{{ route('welcome') }}" aria-label="@lang('app.home.navTitle')">
                <x-icon name="home" size="2" />
            </a>
            <a class="page-head__crumb" href="{{ route('dashboard') }}" aria-label="@lang('app.dashboard.title')">
                @lang('app.dashboard.navTitle')
            </a>
        </nav>
        <h1 class="page-head__title">@lang('app.dashboard.title')</h1>
        <aside class="page-head__description">
            @lang('app.dashboard.description')
        </aside>
    </header>

    <section class="app-section">
        <header class="app-section__head">
            <h1>@lang('app.dashboard.theme.sectionTitle')</h1>
            <p>@lang('app.dashboard.theme.sectionSubtitle')</p>
        </header>
        <div class="app-section__main">
            <div class="app-form in-section">
                <div class="form-row">
                    <div class="label">
                        @lang('app.dashboard.theme.label')
                    </div>
                    <div class="input">
                        <x-switch ref-id="themeToggle" is-checked="1" label-on="dark" label-off="light" data="theme-toggle"/>
                    </div>
                    <div class="descr">@lang("app.dashboard.theme.description")</div>
                </div>
            </div>
        </div>
    </section>

    <section class="app-section">
        <header class="app-section__head">
            <h1>@lang('app.dashboard.session.sectionTitle')</h1>
            <p>@lang('app.dashboard.session.sectionSubtitle')</p>
        </header>
        <div class="app-section__main">
            <div class="app-form in-section">
                <div class="form-row">
                    <ul class="sessions__list">
                        @if(count($sessions) > 0)
                            @foreach($sessions as $session)
                                <li class="session">
                                    @if ($session->agent["is_desktop"])
                                        <x-icon name="desktop" size="2" />
                                    @else
                                        <x-icon name="mobile" size="2" />
                                    @endif
                                    <div class="session__details">
                                        {{ $session->agent["platform"] }} - {{ $session->agent["browser"] }}
                                        @if ($session->is_current_device)
                                            <cite>@lang('app.dashboard.session.thisDevice')</cite>
                                        @else
                                            <small>
                                                @lang('app.dashboard.session.lastActive')
                                                {{ \Carbon\Carbon::createFromTimestamp($session->last_active)->diffForHumans() }}
                                            </small>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        @else
                            <li class="session">
                                @lang('app.dashboard.session.none')
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </section>

    @if(count($availableGames) > 0)
        <section class="app-section">
            <header class="app-section__head">
                <h1>@lang('app.dashboard.availableGames.sectionTitle')</h1>
                <p>@lang('app.dashboard.availableGames.sectionSubtitle')</p>
            </header>
            <div class="app-section__main">
                <div class="app-form in-section">
                    <div class="form-row">
                        <table class="games">
                            @foreach($availableGames as $game)
                            <tr>
                                <td>g{{ $game->number }}</td>
                                <td>{{ count($game->players) }} / {{ $game->max_players }} @lang('app.dashboard.availableGames.players')</td>
                                <td>{{ count($game->stars) }} @lang('app.dashboard.availableGames.stars')</td>
                                <td>
                                    @lang('app.dashboard.availableGames.start')
                                    <time
                                        datetime="{{ \Carbon\Carbon::parse($game->start_date)->format('d.m.Y H:i:s') }}"
                                        title="{{ \Carbon\Carbon::parse($game->start_date)->format('d.m.Y H:i:s') }}">
                                        {{ \Carbon\Carbon::parse($game->start_date)->diffForHumans() }}
                                    </time>
                                </td>
                                <td class="games__enlist">
                                    <a
                                        class="app-btn small both"
                                        href="{{ route('show-enlist', ['game' => $game->id]) }}">
                                        <x-icon name="games" size="2" />
                                        <span>
                                            @lang('app.dashboard.availableGames.enlist')
                                        </span>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if(count($players) > 0)
        <section class="app-section">
            <header class="app-section__head">
                <h1>@lang('app.dashboard.players.sectionTitle')</h1>
                <p>@lang('app.dashboard.players.sectionSubtitle')</p>
            </header>
            <div class="app-section__main">
                <div class="app-form in-section">
                    <div class="form-row">
                        <table class="games">
                            @foreach($players as $player)
                                <tr>
                                    <td>g{{ $player->game->number }}</td>
                                    <td>{{ count($player->game->players) }} / {{ $player->game->max_players }} @lang('app.dashboard.availableGames.players')</td>
                                    <td>[{{ $player->ticker }}]</td>
                                    <td>
                                        @lang('app.dashboard.availableGames.start')
                                        <time
                                            datetime="{{ \Carbon\Carbon::parse($player->game->start_date)->format('d.m.Y H:i:s') }}"
                                            title="{{ \Carbon\Carbon::parse($player->game->start_date)->format('d.m.Y H:i:s') }}">
                                            {{ \Carbon\Carbon::parse($player->game->start_date)->diffForHumans() }}
                                        </time>
                                    </td>
                                    <td class="games__enlist">
                                        <button class="app-btn small both" data-modal="quitGameModal">
                                            <x-icon name="delete" size="2" />
                                            <span>
                                                @lang('app.dashboard.players.quit')
                                            </span>
                                        </button>
                                        <x-modal title="{{ __('app.dashboard.players.quitTitle', ['game' => $player->game->number]) }}" ref-id="quitGameModal">
                                            @lang('app.dashboard.players.quitWarning')
                                            <div class="app-form in-section">
                                                <div class="form-row">
                                                    <div class="input input--justify">
                                                        <button class="app-btn both" data-cancel>
                                                            <x-icon name="cancel" size="2" />
                                                            <span>
                                                                @lang('app.dashboard.delete.cancel')
                                                            </span>
                                                        </button>
                                                        &nbsp;
                                                        <form action="{{ route('quit', ['player' => $player->id ]) }}" METHOD="POST">
                                                            @csrf
                                                            <button class="app-btn app-btn--submit primary both">
                                                                <x-icon name="warning" size="2" />
                                                                <span>
                                                                    @lang('app.dashboard.players.quitSubmit', ['game' => $player->game->number])
                                                                    <x-loading size="24" />
                                                                </span>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </x-modal>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="app-section">
        <header class="app-section__head">
            <h1>@lang('app.dashboard.notifications.sectionTitle')</h1>
            <p>@lang('app.dashboard.notifications.sectionSubtitle')</p>
        </header>
        <div class="app-section__main">
            <form class="app-form in-section" action="{{ route('dashboard-optin') }}" METHOD="POST">
                @csrf

                <div class="form-row has-divider">
                    <div class="input no-label">
                        <x-checkbox
                            selected="{{ Auth::user()->game_mail_optin ? '1' : '0' }}"
                            ref-id="dashboard-optin"
                            error="{{ $errors->has('confirmed') ? '1' : '0' }}">
                            @lang('app.dashboard.notifications.label')
                        </x-checkbox>
                    </div>
                    <div class="descr">@lang("app.dashboard.notifications.description")</div>
                </div>

                <div class="form-row">
                    <div class="input button">
                        <button class="app-btn app-btn--submit both">
                            <x-icon name="mail" size="2" />
                            <span>
                                @lang('app.dashboard.notifications.submit')
                                <x-loading size="24" />
                            </span>
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </section>

    <section class="app-section">
        <header class="app-section__head">
            <h1>@lang('app.dashboard.email.sectionTitle')</h1>
            <p>@lang('app.dashboard.email.sectionSubtitle')</p>
        </header>
        <div class="app-section__main">
            <form class="app-form in-section" action="{{ route('dashboard-email') }}" METHOD="POST">
                @csrf

                <div class="form-row has-divider">
                    <div class="label">
                        <label for="email-email">@lang("app.dashboard.email.emailLabel")</label>
                    </div>
                    <div class="input">
                        <input
                            type="email"
                            name="email-email"
                            id="email-email"
                            class="form-control @error('email-email') invalid @enderror"
                            required
                            aria-required="true"
                            maxlength="120"
                            placeholder="@lang('app.dashboard.email.emailPlaceholder')"
                            value="{{ old('email-email') ?? Auth::user()->email }}" />
                        <aside class="addon @error('email-email') invalid @enderror"><x-icon name="mail" size="2" /></aside>
                    </div>
                    @error('email-email')
                    <div class="error">
                        <ul class="errors">
                            <li><x-icon name="warning" size="2" /> {{ $message }}</li>
                        </ul>
                    </div>
                    @enderror
                    <div class="descr">@lang("app.dashboard.email.description")</div>
                </div>

                <div class="form-row">
                    <div class="input button">
                        <button class="app-btn app-btn--submit both">
                            <x-icon name="lightbulb" size="2" />
                            <span>
                                @lang('app.dashboard.email.submit')
                                <x-loading size="24" />
                            </span>
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </section>

    <section class="app-section">
        <header class="app-section__head">
            <h1>@lang('app.dashboard.password.sectionTitle')</h1>
            <p>@lang('app.dashboard.password.sectionSubtitle')</p>
        </header>
        <div class="app-section__main">
            <form class="app-form in-section" action="{{ route('dashboard-password') }}" METHOD="POST">
                @csrf

                <div class="form-row has-divider">
                    <div class="label">
                        <label for="current-password">@lang("app.dashboard.password.currentPasswordLabel")</label>
                    </div>
                    <div class="input">
                        <input
                            type="password"
                            name="current-password"
                            id="current-password"
                            class="form-control @error('current-password') invalid @enderror"
                            required
                            aria-required="true"
                            placeholder="@lang('app.dashboard.password.currentPasswordPlaceholder')"
                            value="{{ old('current-password') }}" />
                        <aside class="addon @error('current-password') invalid @enderror"><x-icon name="lock" size="2" /></aside>
                    </div>
                    @error('current-password')
                    <div class="error">
                        <ul class="errors">
                            <li><x-icon name="warning" size="2" /> {{ $message }}</li>
                        </ul>
                    </div>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="label">
                        <label for="current-password">@lang("app.dashboard.password.newPasswordLabel")</label>
                    </div>
                    <div class="input">
                        <input
                            type="password"
                            name="new-password"
                            id="new-password"
                            class="form-control @error('new-password') invalid @enderror"
                            required
                            aria-required="true"
                            placeholder="@lang('app.dashboard.password.newPasswordPlaceholder')"
                        />
                        <aside class="addon @error('new-password') invalid @enderror"><x-icon name="lock" size="2" /></aside>
                    </div>
                    <div class="meter">
                        <x-password-strength />
                    </div>
                    @error('new-password')
                    <div class="error">
                        <ul class="errors">
                            <li><x-icon name="warning" size="2" /> {{ $message }}</li>
                        </ul>
                    </div>
                    @enderror
                </div>

                <div class="form-row has-divider">
                    <div class="label">
                        <label for="new-password_confirmation">@lang("app.dashboard.password.repeatPasswordLabel")</label>
                    </div>
                    <div class="input">
                        <input
                            type="password"
                            name="new-password_confirmation"
                            id="new-password_confirmation"
                            class="form-control"
                            required
                            aria-required="true"
                            placeholder="@lang('app.dashboard.password.repeatPasswordPlaceholder')"
                        />
                        <aside class="addon"><x-icon name="lock" size="2" /></aside>
                    </div>
                </div>

                <div class="form-row">
                    <div class="input button">
                        <button class="app-btn app-btn--submit both">
                            <x-icon name="key" size="2" />
                            <span>
                                @lang('app.dashboard.password.submit')
                                <x-loading size="24" />
                            </span>
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </section>

    <section class="app-section">
        <header class="app-section__head">
            <h1>@lang('app.dashboard.delete.sectionTitle')</h1>
            <p>@lang('app.dashboard.delete.sectionSubtitle')</p>
        </header>
        <div class="app-section__main">
            <div class="app-form in-section">
                <div class="form-row has-divider">
                    <div class="descr">@lang("app.dashboard.delete.description")</div>
                </div>
                <div class="form-row">
                    <div class="input button">
                        <button class="app-btn app-btn--submit primary both" data-modal="deleteAccountModal">
                            <x-icon name="warning" size="2" />
                            <span>
                                @lang('app.dashboard.delete.submit')
                                <x-loading size="24" />
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <x-modal :title="__('app.dashboard.delete.sectionTitle')" ref-id="deleteAccountModal">
        @lang('app.dashboard.delete.warning')
        <div class="app-form in-section">
            <div class="form-row">
                <div class="input input--justify">
                    <button class="app-btn both" data-cancel>
                        <x-icon name="cancel" size="2" />
                        <span>
                            @lang('app.dashboard.delete.cancel')
                        </span>
                    </button>
                    &nbsp;
                    <form action="{{ route('dashboard-delete') }}" METHOD="POST">
                        @csrf
                        <button class="app-btn app-btn--submit primary both">
                            <x-icon name="warning" size="2" />
                            <span>
                                @lang('app.dashboard.delete.confirmSubmit')
                                <x-loading size="24" />
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </x-modal>

@endsection
