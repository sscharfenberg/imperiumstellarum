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
        </nav>
        <h1 class="page-head__title">@lang('admin.home.title')</h1>
        <aside class="page-head__description">
            @lang('admin.home.description')
        </aside>
    </header>

    <main class="admin-section">
        <header class="admin-section__head">
            <h1>@lang('admin.home.users.sectionTitle')</h1>
            <p>@lang('admin.home.users.sectionSubtitle')</p>
        </header>
        <div class="admin-section__body">
            <dl>
                <dt>@lang('admin.home.users.total')</dt>
                <dd>{{ number_format($userCount,0, ',', '.') }}</dd>
            </dl>
            <dl>
                <dt>@lang('admin.home.users.unverified')</dt>
                <dd>{{ number_format($unverifiedCount,0, ',', '.') }}</dd>
            </dl>
            <dl>
                <dt>@lang('admin.home.users.suspended')</dt>
                <dd>{{ number_format($suspendedUsers,0, ',', '.') }}</dd>
            </dl>
            <nav class="admin-section__actions app-btn-group app-btn-group--end">
                <a class="app-btn both" href="{{ route('users') }}">
                    <x-icon name="group" size="2" />
                    <span>
                        @lang('admin.users.title')
                    </span>
                </a>
            </nav>
        </div>
    </main>

    <main class="admin-section">
        <header class="admin-section__head">
            <h1>@lang('admin.home.games.sectionTitle')</h1>
            <p>@lang('admin.home.games.sectionSubtitle')</p>
        </header>
        <div class="admin-section__body">
            <dl>
                <dt>@lang('admin.home.games.active')</dt>
                <dd>{{ $activeGames }}</dd>
            </dl>
            <dl>
                <dt>@lang('admin.home.games.notstarted')</dt>
                <dd>{{ $gamesToStart }}</dd>
            </dl>
            <dl>
                <dt>@lang('admin.home.games.canEnlist')</dt>
                <dd>{{ $enlistableGames }}</dd>
            </dl>
            <dl>
                <dt>@lang('admin.home.games.finished')</dt>
                <dd>{{ $finishedGames }}</dd>
            </dl>
            <nav class="admin-section__actions app-btn-group app-btn-group--end">
                @if(Auth::user()->isAdmin())
                    <a class="app-btn both" href="{{ route('show-create-game') }}">
                        <x-icon name="add" size="2" />
                        <span>
                            @lang('admin.game.create.title')
                        </span>
                    </a>
                @endif
                <a class="app-btn both" href="{{ route('games') }}">
                    <x-icon name="search" size="2" />
                    <span>
                        @lang('admin.games.title')
                    </span>
                </a>
            </nav>
        </div>
    </main>

    <main class="admin-section">
        <header class="admin-section__head">
            <h1>@lang('admin.home.reports.sectionTitle')</h1>
            <p>@lang('admin.home.reports.sectionSubtitle')</p>
        </header>
        <div class="admin-section__body">
            <dl>
                <dt>@lang('admin.home.reports.unresolved')</dt>
                <dd>{{ $reportsUnresolved }}</dd>
            </dl>
            <dl>
                <dt>@lang('admin.home.reports.resolved')</dt>
                <dd>{{ $reportsResolved }}</dd>
            </dl>
            <dl>
                <dt>@lang('admin.home.reports.total')</dt>
                <dd>{{ $reportsTotal }}</dd>
            </dl>
            <nav class="admin-section__actions app-btn-group app-btn-group--end">
                <a class="app-btn both" href="{{ route('reports') }}">
                    <x-icon name="warning" size="2" />
                    <span>
                        @lang('admin.reports.title')
                    </span>
                </a>
            </nav>
        </div>
    </main>

@endsection
