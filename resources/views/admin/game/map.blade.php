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
            <a class="page-head__crumb"
               href="{{ route('preview-map', ['game' => $game->id]) }}"
               aria-label="@lang('admin.game.seed.title', ['game' => $game->number])">
                @lang('admin.game.seed.navTitle', ['game' => $game->number])
            </a>
        </nav>
        <h1 class="page-head__title">@lang('admin.game.seed.title', ['game' => $game->number])</h1>
    </header>


    @if(isset($map) && $map)
        <div class="map">
            <header>
                <div
                    data-spectral
                    aria-label="@lang('game.empire.star.spectralType')"
                    role="presentation"></div>
                <h1>@lang("admin.game.seed.mapTitle")</h1>
            </header>
            <div class="map__totals">
                @lang("admin.game.seed.totals"):
                {{ $sectors["1"] }} 🌏 @lang("admin.game.seed.npcStars") -
                {{ $sectors["2"] }} 🌕 @lang("admin.game.seed.playerStars")
            </div>
            <div class="map__wrapper">
                <table class="map__table">
                    @foreach($map as $index=>$row)
                        <tr>
                            @foreach($row as $column)
                                <td>@if($column === 0)•@elseif($column === 1)🌏@elseif($column === 2)🌕@endif</td>
                            @endforeach
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="map__actions">
                <form action="{{ route('seed-game', ['game' => $game->id]) }}" METHOD="POST">
                    @csrf
                    <button class="app-btn app-btn--submit both primary">
                        <x-icon name="save" size="2" />
                        <span>
                            @lang('admin.game.seed.submit')
                            <x-loading size="24" />
                        </span>
                    </button>
                </form>
            </div>
        </div>
    @endif


    <form class="app-form has-headline" action="{{ route('preview-map', ['game' => $game->id]) }}" METHOD="POST">
    @csrf

        <div class="headline" role="heading">
            <div
                data-spectral
                aria-label="@lang('game.empire.star.spectralType')"
                role="presentation"></div>
            <span>@lang("admin.game.seed.headline")</span>
        </div>
        <div class="intro">@lang("admin.game.seed.intro")</div>

        <div class="form-row has-divider">
            <div class="label">
                <label for="dimensions">@lang("admin.game.seed.dimensionsLabel")</label>
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
                    placeholder="@lang('admin.game.seed.dimensionsPlaceholder')"
                    value="{{ $game->dimensions ?? '' }}" />
                <aside class="addon"><x-icon name="location" size="2" /></aside>
            </div>
        </div>

        {{ old('npcDistanceMin') }}

        <div class="form-row has-divider">
            <div class="label">
                <label for="dimensions">@lang("admin.game.seed.npcDistanceLabel")</label>
            </div>
            <div class="input">
                <div class="slider" id="npcDistanceSlider" data-min="1" data-max="20" data-slider>
                    <input
                        class="slider__input"
                        type="hidden"
                        name="npcDistanceMin"
                        id="npcDistanceMin"
                        value="{{ old('npcDistanceMin') ?? '3' }}" />
                    <input
                        class="slider__input"
                        type="hidden"
                        name="npcDistanceMax"
                        id="npcDistanceMax"
                        value="{{ old('npcDistanceMax') ?? '5' }}" />
                </div>
            </div>
            <div class="descr">@lang("admin.game.seed.npcDistanceDescription")</div>
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label for="dimensions">@lang("admin.game.seed.playerDistanceLabel")</label>
            </div>
            <div class="input">
                <div class="slider" id="playerDistanceSlider" data-min="1" data-max="20" data-slider>
                    <input
                        class="slider__input"
                        type="hidden"
                        name="playerDistanceMin"
                        id="playerDistanceMin"
                        value="{{ old('playerDistanceMin') ?? '6' }}" />
                    <input
                        class="slider__input"
                        type="hidden"
                        name="playerDistanceMax"
                        id="playerDistanceMax"
                        value="{{ old('playerDistanceMax') ?? '12' }}" />
                </div>
            </div>
            <div class="descr">@lang("admin.game.seed.playerDistanceDescription")</div>
        </div>

        <div class="form-row">
            <div class="input no-label">
                <textarea class="hidden" name="map" id="map"></textarea>
                <button class="app-btn both primary" data-seed-map>
                    <x-icon name="location" size="2" />
                    <span>
                        @lang('admin.game.seed.preview')
                        <x-loading size="24" />
                    </span>
                </button>
            </div>
        </div>

    </form>


@endsection
