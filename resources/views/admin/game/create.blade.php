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
            <a class="page-head__crumb" href="{{ route('show-create-game') }}" aria-label="@lang('admin.game.create.title')">
                @lang('admin.game.create.navTitle')
            </a>
        </nav>
        <h1 class="page-head__title">@lang('admin.game.create.title')</h1>
    </header>

    <form class="app-form has-headline" action="{{ route('show-create-game') }}" METHOD="POST">
    @csrf

        <div class="headline" role="heading">
            <div
                data-spectral
                aria-label="@lang('game.empire.star.spectralType')"
                role="presentation"></div>
            <span>@lang('admin.game.create.headline')</span>
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
                    value="{{ old('dimensions') ?? '40' }}" />
                <aside class="addon @error('dimensions') invalid @enderror"><x-icon name="location" size="2" /></aside>
            </div>
            @error('dimensions')
            <div class="error">
                <ul class="errors">
                    <li><x-icon name="warning" size="2" /> {{ $message }}</li>
                </ul>
            </div>
            @enderror
            <div class="descr">@lang('admin.game.create.dimensionsDescription')</div>
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label for="start_date">@lang("admin.game.create.startDateLabel")</label>
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
                    value="{{ old('start_date') }}" />
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
            <div class="descr">@lang('admin.game.create.startDateDescription')</div>
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
                    value="{{ old('turn_duration') ?? '15' }}" />
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

        <div class="form-row">
            <div class="input no-label">
                <button class="app-btn app-btn--submit both primary">
                    <x-icon name="save" size="2" />
                    <span>
                        @lang('admin.game.create.submit')
                        <x-loading size="24" />
                    </span>
                </button>
            </div>
        </div>

    </form>

@endsection
