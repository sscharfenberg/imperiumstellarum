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
            <a class="page-head__crumb" href="{{ route('show-enlist', ['game' => $game->id]) }}" aria-label="@lang('app.enlist.title', ['game' => $game->number])">
                @lang('app.enlist.navTitle', ['game' => $game->number])
            </a>
        </nav>
        <h1 class="page-head__title">@lang('app.enlist.title', ['game' => $game->number])</h1>
    </header>

    <form class="app-form has-headline" action="{{ route('enlist', ['game' => $game->id]) }}" METHOD="POST">

        @csrf
        <div class="headline" role="heading">
            <div
                data-spectral
                aria-label="@lang('game.empire.star.spectralType')"
                role="presentation"></div>
            <span>@lang('app.enlist.headline')</span>
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label for="email">@lang("app.enlist.game")</label>
            </div>
            <div class="input">
                g{{ $game->number }}
            </div>
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label for="email">@lang("app.enlist.turnDuration")</label>
            </div>
            <div class="input">{{ $game->turn_duration }} @lang("app.enlist.minutes")</div>
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label for="email">@lang("app.enlist.start")</label>
            </div>
            <div class="input">
                {{ \Carbon\Carbon::parse($game->start_date)->format('d.m.Y H:i') }}
                ({{ \Carbon\Carbon::parse($game->start_date)->diffForHumans() }})
            </div>
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label for="email">@lang("app.enlist.players")</label>
            </div>
            <div class="input">{{ count($game->players) }} / {{ $game->max_players }}</div>
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label for="email">@lang("app.enlist.stars")</label>
            </div>
            <div class="input">{{ count($game->stars) }}</div>
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label for="email">@lang("app.enlist.dimensions")</label>
            </div>
            <div class="input">{{ $game->dimensions }} x {{ $game->dimensions }}</div>
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label for="empire_name">@lang("app.enlist.nameLabel")</label>
            </div>
            <div class="input">
                <input
                    type="text"
                    name="empire_name"
                    id="empire_name"
                    required
                    aria-required="true"
                    maxlength="{{ config('rules.player.name.max') }}"
                    class="form-control @error('empire_name') invalid @enderror"
                    placeholder="@lang('app.enlist.namePlaceholder')"
                    value="{{ old('empire_name') }}" />
                <aside class="addon @error('empire_name') invalid @enderror"><x-icon name="mail" size="2" /></aside>
            </div>
            @error('empire_name')
            <div class="error">
                <ul class="errors">
                    <li><x-icon name="warning" size="2" /> {{ $message }}</li>
                </ul>
            </div>
            @enderror
            <div class="descr">@lang("app.enlist.nameDescription", ['min' => config('rules.player.name.min'), 'max' => config('rules.player.name.max') ])</div>
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label for="empire_name">@lang("app.enlist.tickerLabel")</label>
            </div>
            <div class="input">
                <input
                    type="text"
                    name="ticker"
                    id="ticker"
                    required
                    aria-required="true"
                    maxlength="{{ config('rules.player.ticker.max') }}"
                    class="form-control @error('ticker') invalid @enderror"
                    placeholder="@lang('app.enlist.tickerPlaceholder')"
                    value="{{ old('ticker') }}" />
                <aside class="addon @error('ticker') invalid @enderror"><x-icon name="mail" size="2" /></aside>
            </div>
            @error('ticker')
            <div class="error">
                <ul class="errors">
                    <li><x-icon name="warning" size="2" /> {{ $message }}</li>
                </ul>
            </div>
            @enderror
            <div class="descr">@lang("app.enlist.tickerDescription", ['min' => config('rules.player.ticker.min'), 'max' => config('rules.player.ticker.max') ])</div>
        </div>

        <div class="form-row">
            <div class="input no-label">
                <button class="app-btn app-btn--submit both primary">
                    <x-icon name="lightbulb" size="2" />
                    <span>
                        @lang('app.enlist.submit')
                        <x-loading size="24" />
                    </span>
                </button>
            </div>
        </div>

    </form>

@endsection
