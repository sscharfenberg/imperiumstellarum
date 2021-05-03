@extends('layouts.app')

@section('content')

    <header class="page-head">
        <h1 class="page-head__title">@lang('app.home.title')</h1>
        <aside class="page-head__description">
            @lang('app.home.description')
        </aside>
    </header>

    <section class="hero">
        <span class="hero__text">@lang('app.home.hero.left')</span>
        <span class="hero__text">@lang('app.home.hero.middle')</span>
        <span class="hero__text">@lang('app.home.hero.right')</span>
    </section>

    <ul class="stats">
        <li class="stats__item">
            <header class="stats__headline">@lang('app.home.stats.active.headline')</header>
            @if($activeGames->count() > 0)
                @foreach($activeGames as $game)
                    <article class="stats__game">
                        <span title="@lang('app.home.stats.active.game', ['game' => $game->number])">
                            <x-icon name="games" size="2" />g{{ $game->number }}
                        </span>
                        <span title="@lang('app.home.stats.active.turn', ['turn' => $game->turns->max('number')])">
                            <x-icon name="turn" size="2" />t{{ $game->turns->max('number') }}
                        </span>
                        <span title="@lang('app.home.stats.active.dimensions', ['dimensions' => $game->dimensions])">
                            <x-icon name="location" size="2" />{{ $game->dimensions }}x{{ $game->dimensions }}
                        </span>
                        <span title="@lang('app.home.stats.active.players', ['players' => $game->players->count()])">
                            <x-icon name="user" size="2" />{{ $game->players->count() }}
                        </span>
                    </article>
                @endforeach
            @else
                @lang('app.home.stats.active.none')
            @endif
        </li>
        <li class="stats__item">
            <header class="stats__headline">@lang('app.home.stats.finished.headline')</header>
            @if($finishedGames->count() > 0)
                loop games.
            @else
                @lang('app.home.stats.finished.none')
            @endif
        </li>
    </ul>

    <ul class="features features--margin-bottom">
        @foreach($features->shuffle()->take(5) as $feature)
            <li class="features__item {{$feature['extra-class'] ?? ''}}">
                <x-icon name="{{$feature['icon']}}" size="4" />
                <div class="features__text">{!! $feature['html'] !!}</div>
            </li>
        @endforeach
    </ul>

    <!--{{ $activeGames->count() }}-->


@endsection
