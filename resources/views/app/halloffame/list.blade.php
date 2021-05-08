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
        </nav>
        <h1 class="page-head__title">@lang('app.halloffame.title')</h1>
        <aside class="page-head__description">
            @lang('app.halloffame.description')
        </aside>
    </header>

    <form action="{{ route('halloffame') }}" METHOD="POST">
    @csrf

        <div class="pagination">
            <div class="pagination__meta">
                @lang('app.halloffame.pagination.num', ['games' => $games->count(), 'total' => $games->total() ]),
                @lang('app.halloffame.pagination.sort', ['sort' => __('app.halloffame.thead.'.$sortBy), 'dir' => __('app.halloffame.sort.'.$order) ])
            </div>
            <div class="pagination__perpage">
                @lang('app.halloffame.perPage.showing')
                <select name="perPage" data-perpage>
                    <option value="10"{{ $perPage == '10' ? ' selected' : '' }}>10</option>
                    <option value="20"{{ $perPage == '20' ? ' selected' : '' }}>20</option>
                    <option value="50"{{ $perPage == '50' ? ' selected' : '' }}>50</option>
                    <option value="100"{{ $perPage == '100' ? ' selected' : '' }}>100</option>
                </select>
                @lang('app.halloffame.perPage.results')
            </div>
            {{ $games->appends(['sortBy' => $sortBy, 'order' => $order, 'perPage' => $perPage])->links('shared.pagination') }}
        </div>

        <table class="admin-table" data-tr-href="/hall-of-fame/">

            <thead>
            <tr>
                <th class="sortable{{ $sortBy == 'number' ? ' sorted' : '' }}">
                    @lang('app.halloffame.thead.number')
                    <div class="sort">
                        <input type="radio" name="sort" value="number--asc" id="sort_number_asc" {{ $sortBy == 'number' && $order == 'asc' ? 'checked' : '' }} />
                        <label class="asc" for="sort_number_asc"></label>
                        <input type="radio" name="sort" value="number--desc" id="sort_number_desc" {{ $sortBy == 'number' && $order == 'desc' ? 'checked' : '' }} />
                        <label class="desc" for="sort_number_desc"></label>
                    </div>
                </th>
                <th>@lang('app.halloffame.thead.turns')</th>
                <th class="sortable{{ $sortBy == 'end_date' ? ' sorted' : '' }}">
                    @lang('app.halloffame.thead.end_date')
                    <div class="sort">
                        <input type="radio" name="sort" value="end_date--asc" id="sort_end_date_asc" {{ $sortBy == 'end_date' && $order == 'asc' ? 'checked' : '' }} />
                        <label class="asc" for="sort_end_date_asc"></label>
                        <input type="radio" name="sort" value="end_date--desc" id="sort_end_date_desc" {{ $sortBy == 'end_date' && $order == 'desc' ? 'checked' : '' }} />
                        <label class="desc" for="sort_end_date_desc"></label>
                    </div>
                </th>
                <th>@lang('app.halloffame.thead.dimensions')</th>
                <th>@lang('app.halloffame.thead.players')</th>
                <th>@lang('app.halloffame.thead.winner')</th>
            </tr>
            </thead>

            <tbody>
                @if($games->count() > 0)
                    @foreach ($games as $game)
                        <tr data-id="{{ $game->id }}">
                            <td>g{{ $game->number }}</td>
                            <td>{{ $game->turns }}</td>
                            <td>{{ $game->end_date->format($format) }}</td>
                            <td>{{ $game->dimensions }}x{{ $game->dimensions }}</td>
                            <td>{{ $game->participants->count() }}</td>
                            <td class="highlight">[{{ $game->winner->ticker }}] {{ $game->winner->name }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6">@lang('app.halloffame.none')</td>
                    </tr>
                @endif
            </tbody>

        </table>

        <div class="pagination">
            <div class="pagination__meta">
                @lang('app.halloffame.pagination.num', ['games' => $games->count(), 'total' => $games->total() ]),
                @lang('app.halloffame.pagination.sort', ['sort' => __('app.halloffame.thead.'.$sortBy), 'dir' => __('app.halloffame.sort.'.$order) ])
            </div>
            {{ $games->appends(['sortBy' => $sortBy, 'order' => $order, 'perPage' => $perPage])->links('shared.pagination') }}
        </div>

    </form>

@endsection
