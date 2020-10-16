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
            <a
                class="page-head__crumb"
                href="{{ route('game-players', ['game' => $game->id]) }}"
                aria-label="@lang('admin.game.players.title', ['game' => $game->number])">
                @lang('admin.game.players.navTitle', ['game' => $game->number])
            </a>
        </nav>
        <h1 class="page-head__title">@lang('admin.game.players.title', ['game' => $game->number])</h1>
    </header>

    <form action="{{ route('game-players', ['game' => $game->id]) }}" METHOD="POST">
        @csrf

        <div class="pagination">
            <div class="pagination__meta">
                @lang('admin.game.players.pagination.num', ['players' => $players->count(), 'total' => $players->total() ]),
                @lang('admin.game.players.pagination.sort', ['sort' => __('admin.game.players.thead.'.$sortBy), 'dir' => __('admin.games.sort.'.$order) ])
            </div>
            <div class="pagination__perpage">
                @lang('admin.games.perPage.showing')
                <select name="perPage" data-perpage>
                    <option value="10"{{ $perPage == '10' ? ' selected' : '' }}>10</option>
                    <option value="20"{{ $perPage == '20' ? ' selected' : '' }}>20</option>
                    <option value="50"{{ $perPage == '50' ? ' selected' : '' }}>50</option>
                    <option value="100"{{ $perPage == '100' ? ' selected' : '' }}>100</option>
                </select>
                @lang('admin.games.perPage.results')
            </div>
            {{ $players->appends(['sortBy' => $sortBy, 'order' => $order, 'perPage' => $perPage])->links('shared.pagination') }}
        </div>

        <table class="admin-table">

            <thead>
            <tr>
                <th>@lang('admin.game.players.thead.id')</th>
                <th>@lang('admin.game.players.thead.email')</th>
                <th>@lang('admin.game.players.thead.isSuspended')</th>
                <th>@lang('admin.game.players.thead.locale')</th>
                <th class="sortable{{ $sortBy == 'created_at' ? ' sorted' : '' }}">
                    @lang('admin.game.players.thead.created_at')
                    <div class="sort">
                        <input type="radio" name="sort" value="created_at--asc" id="sort_created_at_asc" {{ $sortBy == 'created_at' && $order == 'asc' ? 'checked' : '' }} />
                        <label class="asc" for="sort_created_at_asc"></label>
                        <input type="radio" name="sort" value="created_at--desc" id="sort_created_at_desc" {{ $sortBy == 'created_at' && $order == 'desc' ? 'checked' : '' }} />
                        <label class="desc" for="sort_created_at_desc"></label>
                    </div>
                </th>
                <th class="sortable{{ $sortBy == 'ticker' ? ' sorted' : '' }}">
                    @lang('admin.game.players.thead.ticker')
                    <div class="sort">
                        <input type="radio" name="sort" value="ticker--asc" id="sort_ticker_asc" {{ $sortBy == 'ticker' && $order == 'asc' ? 'checked' : '' }} />
                        <label class="asc" for="sort_ticker_asc"></label>
                        <input type="radio" name="sort" value="ticker--desc" id="sort_ticker_desc" {{ $sortBy == 'ticker' && $order == 'desc' ? 'checked' : '' }} />
                        <label class="desc" for="sort_ticker_desc"></label>
                    </div>
                </th>
                <th class="sortable{{ $sortBy == 'name' ? ' sorted' : '' }}">
                    @lang('admin.game.players.thead.name')
                    <div class="sort">
                        <input type="radio" name="sort" value="name--asc" id="sort_name_asc" {{ $sortBy == 'name' && $order == 'asc' ? 'checked' : '' }} />
                        <label class="asc" for="sort_name_asc"></label>
                        <input type="radio" name="sort" value="name--desc" id="sort_name_desc" {{ $sortBy == 'name' && $order == 'desc' ? 'checked' : '' }} />
                        <label class="desc" for="sort_name_desc"></label>
                    </div>
                </th>
            </tr>
            </thead>

            <tbody>
            @foreach ($players as $player)
                <tr>
                    <td>{{$player->user->id}}</td>
                    <td>{{$player->user->email}}</td>
                    <td>
                        @if(in_array($player->user->id, $suspensions))
                            <x-icon name="warning" size="2" />
                        @endif
                    </td>
                    <td class="locale"><img src="/assets/images/flags/{{ $player->user->locale }}.svg" /></td>
                    <td>
                        <time
                            datetime="{{ \Carbon\Carbon::parse($player->created_at)->format('d.m.Y H:i:s') }}"
                            title="{{ \Carbon\Carbon::parse($player->created_at)->format('d.m.Y H:i:s') }}">
                            {{ \Carbon\Carbon::parse($player->created_at)->diffForHumans() }}
                        </time>
                    </td>
                    <td>{{ $player->ticker }}</td>
                    <td>{{ $player->name }}</td>
                </tr>
            @endforeach
            </tbody>

        </table>
    </form>

@endsection
