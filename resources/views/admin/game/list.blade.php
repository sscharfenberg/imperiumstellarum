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
        </nav>
        <h1 class="page-head__title">@lang('admin.games.title')</h1>
    </header>

    @if(Auth::user()->isAdmin())
        <a class="app-btn both" href="{{ route('show-create-game') }}" style="display: inline-flex; margin-bottom: 20px;">
            <x-icon name="add" size="2" />
            <span>
                @lang('admin.game.create.title')
            </span>
        </a>
    @endif

    <form action="{{ route('games') }}" METHOD="POST">
    @csrf

        <div class="pagination">
            <div class="pagination__meta">
                @lang('admin.games.pagination.num', ['games' => $games->count(), 'total' => $games->total() ]),
                @lang('admin.games.pagination.sort', ['sort' => __('admin.games.thead.'.$sortBy), 'dir' => __('admin.games.sort.'.$order) ])
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
            {{ $games->appends(['sortBy' => $sortBy, 'order' => $order, 'perPage' => $perPage])->links('shared.pagination') }}
        </div>

        <table class="admin-table" data-tr-href="/admin/game/">
            <thead>
            <tr>
                <th class="sortable{{ $sortBy == 'number' ? ' sorted' : '' }}">
                    @lang('admin.games.thead.number')
                    <div class="sort">
                        <input type="radio" name="sort" value="number--asc" id="sort_number_asc" {{ $sortBy == 'number' && $order == 'asc' ? 'checked' : '' }} />
                        <label class="asc" for="sort_number_asc"></label>
                        <input type="radio" name="sort" value="number--desc" id="sort_number_desc" {{ $sortBy == 'number' && $order == 'desc' ? 'checked' : '' }} />
                        <label class="desc" for="sort_number_desc"></label>
                    </div>
                </th>
                <th>@lang('admin.games.thead.active')</th>
                <th>@lang('admin.games.thead.processing')</th>
                <th>@lang('admin.games.thead.can_enlist')</th>
                <th>@lang('admin.games.thead.players')</th>
                <th class="sortable{{ $sortBy == 'start_date' ? ' sorted' : '' }}">
                    @lang('admin.games.thead.start_date')
                    <div class="sort">
                        <input type="radio" name="sort" value="start_date--asc" id="sort_start_date_asc" {{ $sortBy == 'start_date' && $order == 'asc' ? 'checked' : '' }} />
                        <label class="asc" for="sort_start_date_asc"></label>
                        <input type="radio" name="sort" value="start_date--desc" id="sort_start_date_desc" {{ $sortBy == 'start_date' && $order == 'desc' ? 'checked' : '' }} />
                        <label class="desc" for="sort_start_date_desc"></label>
                    </div>
                </th>
                <th>@lang('admin.games.thead.turn')</th>
                <th>@lang('admin.games.thead.turn_due')</th>
                <th>@lang('admin.games.thead.seeded')</th>
            </tr>
            </thead>
            <tbody>
            @if($games->count() !== 0)
                @foreach ($games as $game)
                    <tr data-id="{{ $game->id }}">
                        <td>g{{ $game->number }}</td>
                        <td>
                            @if($game->active)
                                <span class="symbol success">
                                    <x-icon name="done" size="2" />
                                </span>
                            @else
                                <span class="symbol error">
                                    <x-icon name="cancel" size="2" />
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($game->processing)
                                <span class="symbol success">
                                    <x-icon name="done" size="2" />
                                </span>
                            @else
                                <span class="symbol error">
                                    <x-icon name="cancel" size="2" />
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($game->can_enlist)
                                <span class="symbol success">
                                    <x-icon name="done" size="2" />
                                </span>
                            @else
                                <span class="symbol error">
                                    <x-icon name="cancel" size="2" />
                                </span>
                            @endif
                        </td>
                        <td>
                            {{ $game->players()->count() }} / {{ $game->max_players }}
                        </td>
                        <td>
                            <time
                                datetime="{{ \Carbon\Carbon::parse($game->start_date)->format('d.m.Y H:i:s') }}"
                                title="{{ \Carbon\Carbon::parse($game->start_date)->format('d.m.Y H:i:s') }}">
                                {{ \Carbon\Carbon::parse($game->start_date)->diffForHumans() }}
                            </time>
                        </td>
                        <td>
                            @if(count($game->turns) === 0)
                                @lang('admin.games.tbody.notStarted')
                            @else
                                @foreach($turns as $turn)
                                    @if($turn->game_id === $game->id)
                                        {{ $turn->number }}
                                    @endif
                                @endforeach
                            @endif
                        </td>
                        <td>
                            @if(count($game->turns) === 0)
                                @lang('admin.games.tbody.notStarted')
                            @else
                                @foreach($turns as $turn)
                                    @if($turn->game_id === $game->id)
                                        <time
                                            datetime="{{ \Carbon\Carbon::parse($turn->due)->format('d.m.Y H:i:s') }}"
                                            title="{{ \Carbon\Carbon::parse($turn->due)->format('d.m.Y H:i:s') }}">
                                            {{ \Carbon\Carbon::parse($turn->due)->diffForHumans() }}
                                        </time>
                                    @endif
                                @endforeach
                            @endif
                        </td>
                        <td>
                            @if($game->stars)
                                <span class="symbol success">
                                    <x-icon name="done" size="2" />
                                </span>
                            @else
                                <span class="symbol error">
                                    <x-icon name="cancel" size="2" />
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="9">no games yet.</td>
                </tr>
            @endif
            </tbody>
        </table>

        <div class="pagination">
            <div class="pagination__meta">
                @lang('admin.games.pagination.num', ['games' => $games->count(), 'total' => $games->total() ]),
                @lang('admin.games.pagination.sort', ['sort' => __('admin.games.thead.'.$sortBy), 'dir' => __('admin.games.sort.'.$order) ])
            </div>
            {{ $games->appends(['sortBy' => $sortBy, 'order' => $order, 'perPage' => $perPage])->links('shared.pagination') }}
        </div>


    </form>

@endsection
