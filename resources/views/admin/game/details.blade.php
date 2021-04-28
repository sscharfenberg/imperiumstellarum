@extends('layouts.admin')

@section('content')

    <header class="page-head">
        <nav class="page-head__breadcrumbs">
            <a class="page-head__crumb" href="{{ route('welcome') }}" aria-label="@lang('app.home.navTitle')">
                <x-icon name="home" size="2" />
            </a>
            <a class="page-head__crumb" href="{{ route('admin') }}" aria-label="@lang('admin.home.title')">
                @lang('admin.home.navTitle')
            </a>
            <a class="page-head__crumb" href="{{ route('games') }}" aria-label="@lang('admin.games.title')">
                @lang('admin.games.navTitle')
            </a>
            <a
                class="page-head__crumb"
                href="{{ route('game-details', ['game' => $game->id]) }}"
                aria-label="@lang('admin.game.edit.title', ['game' => $game->number])">
                @lang('admin.game.edit.navTitle', ['game' => $game->number])
            </a>
        </nav>
        <h1 class="page-head__title">@lang('admin.game.edit.title', ['game' => $game->number])</h1>
    </header>

    <form class="app-form has-headline" action="{{ route('game-edit', ['game' => $game->id]) }}" METHOD="POST">
    @csrf

        <div class="headline" role="heading">
            <div
                data-spectral
                aria-label="@lang('game.empire.star.spectralType')"
                role="presentation"></div>
            <span>@lang('admin.game.edit.headline', ['game' => $game->number])</span>
        </div>

        <div class="intro">@lang('admin.game.edit.intro')</div>

        <div class="form-row has-divider">
            <div class="label">
                <label>@lang("admin.game.edit.createdLabel")</label>
            </div>
            <div class="input">
                {{ \Carbon\Carbon::parse($game->created_at)->format('d.m.Y H:i') }}
            </div>
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label>@lang("admin.game.edit.updatedLabel")</label>
            </div>
            <div class="input">
                {{ \Carbon\Carbon::parse($game->updated_at)->format('d.m.Y H:i') }}
            </div>
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label>@lang("admin.game.edit.seededLabel")</label>
            </div>
            <div class="input">
                @if(count($game->stars))
                    <span class="symbol success">
                        <x-icon name="done" size="2" />
                    </span>
                @else
                    <span class="symbol error">
                        <x-icon name="cancel" size="2" />
                    </span>
                @endif
            </div>
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label>@lang("admin.game.edit.finished")</label>
            </div>
            <div class="input">
                @if($game->finished)
                    <span class="symbol success">
                        <x-icon name="done" size="2" />
                    </span>
                @else
                    <span class="symbol error">
                        <x-icon name="cancel" size="2" />
                    </span>
                @endif
            </div>
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label for="dimensions">@lang("admin.game.create.dimensionsLabel")</label>
            </div>
            <div class="input">
                <input
                    type="number"
                    name="dimensions"
                    id="dimensions"
                    class="form-control @error('dimensions') invalid @enderror"
                    required
                    aria-required="true"
                    min="10"
                    max="200"
                    placeholder="@lang('admin.game.create.dimensionsPlaceholder')"
                    value="{{ $game->dimensions ?? '' }}" />
                <aside class="addon @error('dimensions') invalid @enderror"><x-icon name="location" size="2" /></aside>
            </div>
            @error('dimensions')
            <div class="error">
                <ul class="errors">
                    <li><x-icon name="warning" size="2" /> {{ $message }}</li>
                </ul>
            </div>
            @enderror
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label for="start_date">@lang("admin.game.edit.startDateLabel")</label>
            </div>
            <div class="input">
                <input
                    type="text"
                    name="start_date"
                    id="start_date"
                    class="form-control @error('start_date') invalid @enderror"
                    required
                    aria-required="true"
                    placeholder="@lang('admin.game.create.startDatePlaceholder')"
                    data-datepicker
                    value="{{ \Carbon\Carbon::parse($game->start_date)->format('d.m.Y H:i') ?? '' }}" />
                <input type="hidden" name="start_date_timezone_offset" data-timezone-offset value="" />
                <aside class="addon @error('start_date') invalid @enderror"><x-icon name="event" size="2" /></aside>
            </div>
            @error('start_date')
            <div class="error">
                <ul class="errors">
                    <li><x-icon name="warning" size="2" /> {{ $message }}</li>
                </ul>
            </div>
            @enderror
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label for="turn_duration">@lang("admin.game.create.turnDurationLabel")</label>
            </div>
            <div class="input">
                <input
                    type="number"
                    name="turn_duration"
                    id="turn_duration"
                    class="form-control @error('turn_duration') invalid @enderror"
                    required
                    aria-required="true"
                    min="1"
                    max="120"
                    placeholder="@lang('admin.game.create.turnDurationPlaceholder')"
                    value="{{ $game->turn_duration ?? '' }}" />
                <aside class="addon @error('turn_duration') invalid @enderror"><x-icon name="turn" size="2" /></aside>
            </div>
            @error('turn_duration')
            <div class="error">
                <ul class="errors">
                    <li><x-icon name="warning" size="2" /> {{ $message }}</li>
                </ul>
            </div>
            @enderror
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label for="can_enlist">@lang("admin.game.edit.canEnlistLabel")</label>
            </div>
            <div class="input">
                <input type="hidden" name="can_enlist" value="0" />
                <x-checkbox
                    selected="{{ $game->can_enlist }}"
                    ref-id="can_enlist"
                    error="">
                </x-checkbox>
            </div>
            <div class="descr">@lang('admin.game.edit.canEnlistDescription')</div>
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label for="active">@lang("admin.game.edit.activeLabel")</label>
            </div>
            <div class="input">
                <input type="hidden" name="active" value="0" />
                <x-checkbox
                    selected="{{ $game->active }}"
                    ref-id="active"
                    error="">
                </x-checkbox>
            </div>
            <div class="descr">@lang('admin.game.edit.activeDescription')</div>
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label for="processing">@lang("admin.game.edit.processingLabel")</label>
            </div>
            <div class="input">
                <input type="hidden" name="processing" value="0" />
                <x-checkbox
                    selected="{{ $game->processing }}"
                    ref-id="processing"
                    error="">
                </x-checkbox>
            </div>
            <div class="descr">@lang('admin.game.edit.processingDescription')</div>
        </div>

        @if($turn)
            <div class="form-row has-divider">
                <div class="label">
                    <label>@lang("admin.game.edit.currentTurnLabel")</label>
                </div>
                <div class="input">
                    {{ $turn->number }}
                </div>
            </div>
        @endif

        <div class="form-row has-divider">
            <div class="label">
                <label>@lang("admin.game.edit.turnDueLabel")</label>
            </div>
            <div class="input">
                @if(count($game->turns) === 0)
                    @lang('admin.game.edit.gameNotStarted')
                @else
                    <time
                        datetime="{{ \Carbon\Carbon::parse($turn->due)->format('d.m.Y H:i:s') }}"
                        title="{{ \Carbon\Carbon::parse($turn->due)->format('d.m.Y H:i:s') }}">
                        {{ \Carbon\Carbon::parse($turn->due)->diffForHumans() }}
                    </time>
                @endif
            </div>
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label>@lang("admin.game.edit.playersLabel")</label>
            </div>
            <div class="input">
                {{ $game->players()->count() }} / {{ $game->max_players }}
            </div>
        </div>

        <div class="form-row">
            <div class="input no-label">
                <button class="app-btn app-btn--submit both">
                    <x-icon name="save" size="2" />
                    <span>
                        @lang('admin.game.edit.submit')
                        <x-loading size="24" />
                    </span>
                </button>

                @if(count($game->players) > 0)
                    <a
                        class="app-btn both"
                        href="{{ route('game-players', ['game' => $game->id]) }}"
                        style="margin-left: auto;">
                        <x-icon name="group" size="2" />
                        <span>
                            @lang('admin.game.players.button')
                        </span>
                    </a>
                @endif

                @if(count($game->stars) === 0)
                    <a
                        class="app-btn both primary"
                        href="{{ route('preview-map', ['game' => $game->id]) }}"
                        style="margin-left: auto;">
                        <x-icon name="starchart" size="2" />
                        <span>
                            @lang('admin.game.edit.seed')
                        </span>
                    </a>
                @endif
            </div>
        </div>

    </form>

@endsection
