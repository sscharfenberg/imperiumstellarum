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
            <a class="page-head__crumb" href="{{ route('reports') }}" aria-label="@lang('admin.reports.title')">
                @lang('admin.reports.navTitle')
            </a>
        </nav>
        <h1 class="page-head__title">@lang('admin.reports.title')</h1>
    </header>

    <form action="{{ route('reports') }}" METHOD="POST">
    @csrf

        <div class="pagination">
            <div class="pagination__meta">
                @lang('admin.reports.pagination.num', ['reports' => $reports->count(), 'total' => $reports->total() ]),
                @lang('admin.reports.pagination.sort', ['sort' => __('admin.reports.thead.'.$sortBy), 'dir' => __('admin.reports.sort.'.$order) ])
            </div>
            <div class="pagination__perpage">
                @lang('admin.reports.perPage.showing')
                <select name="perPage" data-perpage>
                    <option value="2"{{ $perPage == '2' ? ' selected' : '' }}>2</option>
                    <option value="5"{{ $perPage == '5' ? ' selected' : '' }}>5</option>
                    <option value="10"{{ $perPage == '10' ? ' selected' : '' }}>10</option>
                    <option value="20"{{ $perPage == '20' ? ' selected' : '' }}>20</option>
                    <option value="50"{{ $perPage == '50' ? ' selected' : '' }}>50</option>
                    <option value="100"{{ $perPage == '100' ? ' selected' : '' }}>100</option>
                </select>
                @lang('admin.reports.perPage.results')
            </div>
            {{ $reports->appends(['sortBy' => $sortBy, 'order' => $order, 'perPage' => $perPage])->links('shared.pagination') }}
        </div>

        <table class="admin-table" data-tr-href="/admin/report/">
            <thead>
            <tr>
                <th class="sortable{{ $sortBy == 'created_at' ? ' sorted' : '' }}">
                    @lang('admin.reports.thead.created_at')
                    <div class="sort">
                        <input type="radio" name="sort" value="created_at--asc" id="sort_created_at_asc" {{ $sortBy == 'created_at' && $order == 'asc' ? 'checked' : '' }} />
                        <label class="asc" for="sort_created_at_asc"></label>
                        <input type="radio" name="sort" value="created_at--desc" id="sort_created_at_desc" {{ $sortBy == 'created_at' && $order == 'desc' ? 'checked' : '' }} />
                        <label class="desc" for="sort_created_at_desc"></label>
                    </div>
                </th>
                <th>@lang('admin.reports.thead.game')</th>
                <th>@lang('admin.reports.thead.reporter')</th>
                <th>@lang('admin.reports.thead.reportee')</th>
                <th>@lang('admin.reports.thead.comment')</th>
                <th>@lang('admin.reports.thead.resolved')</th>
            </tr>
            </thead>
            @if($reports->count() !== 0)
                <tbody>
                @foreach ($reports as $report)
                    <tr data-id="{{ $report->id }}">
                        <td>
                            <time
                                datetime="{{ \Carbon\Carbon::parse($report->created_at)->format('d.m.Y H:i:s') }}"
                                title="{{ \Carbon\Carbon::parse($report->created_at)->format('d.m.Y H:i:s') }}">
                                {{ \Carbon\Carbon::parse($report->created_at)->diffForHumans() }}
                            </time>
                        </td>
                        <td>g{{$report->game->number}}</td>
                        <td class="locale">
                            [{{$report->reporter->ticker}}]
                            <img src="/assets/images/flags/{{ $report->reporter->user->locale }}.svg" />
                        </td>
                        <td class="locale">
                            [{{$report->reportee->ticker}}]
                            <img src="/assets/images/flags/{{ $report->reportee->user->locale }}.svg" />
                        </td>
                        <td>{{substr($report->comment, 0, 40)}}</td>
                        <td>
                            @if($report->resolved_admin !== null)
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
                </tbody>
            @endif
        </table>

        <div class="pagination">
            <div class="pagination__meta">
                @lang('admin.reports.pagination.num', ['reports' => $reports->count(), 'total' => $reports->total() ]),
                @lang('admin.reports.pagination.sort', ['sort' => __('admin.games.thead.'.$sortBy), 'dir' => __('admin.games.sort.'.$order) ])
            </div>
            {{ $reports->appends(['sortBy' => $sortBy, 'order' => $order, 'perPage' => $perPage])->links('shared.pagination') }}
        </div>

    </form>

@endsection
