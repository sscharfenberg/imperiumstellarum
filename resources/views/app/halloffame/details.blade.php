@extends('layouts.app')

@section('content')

    <header class="page-head">
        <nav class="page-head__breadcrumbs">
            <a class="page-head__crumb" href="{{ route('welcome') }}" aria-label="@lang('app.home.navTitle')">
                <x-icon name="home" size="2" />
            </a>
            <a class="page-head__crumb" href="{{ route('halloffame') }}" aria-label="@lang('app.halloffame.title')">
                @lang('app.halloffame.navTitle')
            </a>
            <a class="page-head__crumb" href="{{ route('halloffame-details', ['id' => $game->id]) }}" aria-label="@lang('app.halloffameDetails.title', ['game' => $game->number])">
                @lang('app.halloffameDetails.navTitle', ['game' => $game->number])
            </a>
        </nav>
        <h1 class="page-head__title">@lang('app.halloffameDetails.title', ['game' => $game->number])</h1>
        <aside class="page-head__description">
            @lang('app.halloffameDetails.description', ['game' => $game->number])
        </aside>
    </header>

    <div class="app-form has-headline">

        <div class="headline" role="heading">
            <div
                data-spectral
                aria-label="@lang('game.empire.star.spectralType')"
                role="presentation"></div>
            <span>@lang('app.halloffameDetails.headline', ['game' => $game->number])</span>
        </div>

        <ul class="hof__meta">
            <li class="hof__detail">
                @lang("app.halloffameDetails.turns")
                <x-icon name="turn" size="2" />
                <span>{{ $game->turns }}</span>
            </li>
            <li class="hof__detail">
                @lang("app.halloffameDetails.dimensions")
                <x-icon name="location" size="2" />
                <span>{{ $game->dimensions }}x{{ $game->dimensions }}</span>
            </li>
            <li class="hof__detail">
                @lang("app.halloffameDetails.players")
                <x-icon name="user" size="2" />
                <span>{{ $game->participants->count() }}</span>
            </li>
            <li class="hof__detail">
                @lang("app.halloffameDetails.startDate")
                <x-icon name="event" size="2" />
                <span>{{ \Carbon\Carbon::parse($game->start_date)->format($format) }}</span>
            </li>
            <li class="hof__detail">
                @lang("app.halloffameDetails.endDate")
                <x-icon name="event" size="2" />
                <span>{{ \Carbon\Carbon::parse($game->end_date)->format($format) }}</span>
            </li>
            <li class="hof__detail">
                @lang("app.halloffameDetails.winner")
                <x-icon name="empire" size="2" />
                <span>[{{ $game->winner->ticker }}] {{ $game->winner->name }}</span>
            </li>
        </ul>
    </div>


    <form action="{{ route('halloffame-details', ['id' => $game->id]) }}" METHOD="POST">
    @csrf

        <div class="pagination">
            <div class="pagination__meta">
                @lang('app.halloffameDetails.pagination.num', ['participants' => $participants->count(), 'total' => $participants->total() ]),
                @lang('app.halloffameDetails.pagination.sort', ['sort' => __('app.halloffameDetails.thead.'.$sortBy), 'dir' => __('app.halloffame.sort.'.$order) ])
            </div>
            <div class="pagination__perpage">
                @lang('app.halloffameDetails.perPage.showing')
                <select name="perPage" data-perpage>
                    <option value="10"{{ $perPage == '10' ? ' selected' : '' }}>10</option>
                    <option value="20"{{ $perPage == '20' ? ' selected' : '' }}>20</option>
                    <option value="50"{{ $perPage == '50' ? ' selected' : '' }}>50</option>
                    <option value="100"{{ $perPage == '100' ? ' selected' : '' }}>100</option>
                </select>
                @lang('app.halloffameDetails.perPage.results')
            </div>
            {{ $participants->appends(['sortBy' => $sortBy, 'order' => $order, 'perPage' => $perPage])->links('shared.pagination') }}
        </div>

        <table class="admin-table">

            <thead>
            <tr>
                <th class="sortable{{ $sortBy == 'ticker' ? ' sorted' : '' }}">
                    @lang('app.halloffameDetails.thead.ticker')
                    <div class="sort">
                        <input type="radio" name="sort" value="ticker--asc" id="sort_ticker_asc" {{ $sortBy == 'ticker' && $order == 'asc' ? 'checked' : '' }} />
                        <label class="asc" for="sort_ticker_asc"></label>
                        <input type="radio" name="sort" value="ticker--desc" id="sort_ticker_desc" {{ $sortBy == 'ticker' && $order == 'desc' ? 'checked' : '' }} />
                        <label class="desc" for="sort_ticker_desc"></label>
                    </div>
                </th>
                <th class="sortable{{ $sortBy == 'name' ? ' sorted' : '' }}">
                    @lang('app.halloffameDetails.thead.name')
                    <div class="sort">
                        <input type="radio" name="sort" value="name--asc" id="sort_name_asc" {{ $sortBy == 'name' && $order == 'asc' ? 'checked' : '' }} />
                        <label class="asc" for="sort_name_asc"></label>
                        <input type="radio" name="sort" value="name--desc" id="sort_name_desc" {{ $sortBy == 'name' && $order == 'desc' ? 'checked' : '' }} />
                        <label class="desc" for="sort_name_desc"></label>
                    </div>
                </th>
                <th class="sortable{{ $sortBy == 'population' ? ' sorted' : '' }}">
                    @lang('app.halloffameDetails.thead.population')
                    <div class="sort">
                        <input type="radio" name="sort" value="population--asc" id="sort_population_asc" {{ $sortBy == 'population' && $order == 'asc' ? 'checked' : '' }} />
                        <label class="asc" for="sort_population_asc"></label>
                        <input type="radio" name="sort" value="population--desc" id="sort_population_desc" {{ $sortBy == 'population' && $order == 'desc' ? 'checked' : '' }} />
                        <label class="desc" for="sort_population_desc"></label>
                    </div>
                </th>
                <th class="sortable{{ $sortBy == 'stars' ? ' sorted' : '' }}">
                    @lang('app.halloffameDetails.thead.stars')
                    <div class="sort">
                        <input type="radio" name="sort" value="stars--asc" id="sort_stars_asc" {{ $sortBy == 'stars' && $order == 'asc' ? 'checked' : '' }} />
                        <label class="asc" for="sort_stars_asc"></label>
                        <input type="radio" name="sort" value="stars--desc" id="sort_stars_desc" {{ $sortBy == 'stars' && $order == 'desc' ? 'checked' : '' }} />
                        <label class="desc" for="sort_stars_desc"></label>
                    </div>
                </th>
                <th>@lang('app.halloffameDetails.thead.ships')</th>
                <th>@lang('app.halloffameDetails.thead.shipyards')</th>
            </tr>
            </thead>

            <tbody>
            @if($participants->count() > 0)
                @foreach ($participants as $player)
                    <tr class="{{ $player->id === $game->winner->id ? 'hof__winner' : '' }}">
                        <td>[{{$player->ticker}}]</td>
                        <td>{{$player->name}}</td>
                        <td>{{ number_format($player->population, 2, ",", ".") }}</td>
                        <td>{{ $player->stars }}</td>
                        <td>
                            @if($player->ships)
                                <ul class="hof__ships">
                                    @foreach($player->ships as $type => $amount)
                                        <li
                                            class="hof__ship"
                                            title="@lang('game.common.hulls.'.$type): {{$amount}}"
                                            aria-label="@lang('game.common.hulls.'.$type): {{$amount}}">
                                            {{ $amount }} <x-icon name="hull-{{ $type }}" size="2" />
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                        <td>
                            @if($player->shipyards)
                                <ul class="hof__ships">
                                    @foreach($player->shipyards as $type => $amount)
                                        <li
                                            class="hof__ship"
                                            title="@lang('game.common.hulls.'.$type)-@lang('app.halloffameDetails.thead.shipyards'): {{$amount}}"
                                            aria-label="@lang('game.common.hulls.'.$type)-@lang('app.halloffameDetails.thead.shipyards'): {{$amount}}">
                                            {{ $amount }} <x-icon name="hull-{{ $type }}" size="2" />
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>

        </table>

        <div class="pagination">
            <div class="pagination__meta">
                @lang('app.halloffameDetails.pagination.num', ['participants' => $participants->count(), 'total' => $participants->total() ]),
                @lang('app.halloffameDetails.pagination.sort', ['sort' => __('app.halloffameDetails.thead.'.$sortBy), 'dir' => __('app.halloffame.sort.'.$order) ])
            </div>
            {{ $participants->appends(['sortBy' => $sortBy, 'order' => $order, 'perPage' => $perPage])->links('shared.pagination') }}
        </div>

    </form>

@endsection
