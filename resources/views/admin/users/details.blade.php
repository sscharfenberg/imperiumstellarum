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
            <a class="page-head__crumb" href="{{ route('users') }}" aria-label="@lang('admin.users.title')">
                @lang('admin.users.navTitle')
            </a>
            <a class="page-head__crumb" href="{{ route('user-details', ['user' => $user->id]) }}" aria-label="@lang('admin.user.title')">
                @lang('admin.user.navTitle', ['id' => $user->id])
            </a>
        </nav>
        <h1 class="page-head__title">@lang('admin.user.title', ['id' => $user->id])</h1>
    </header>

    <form
        class="app-form less-margins"
        action="{{ route('user-change', ['user' => $user->id]) }}"
        METHOD="POST">
        @csrf
        <div class="headline" role="heading">
            <span>@lang('admin.user.data.sectionTitle')</span>
        </div>
        <div class="intro">@lang('admin.user.data.sectionSubtitle')</div>
        <div class="form-row has-divider">
            <div class="label">
                <label for="email">
                    @lang("admin.user.data.emailLabel")
                    &nbsp;<img src="/assets/images/flags/{{ $user->locale }}.svg" width="30" height="15" />
                </label>
            </div>
            <div class="input">
                <input
                    type="email"
                    name="email"
                    id="email"
                    maxlength="120"
                    class="form-control @error('email') invalid @enderror"
                    placeholder="@lang('admin.user.data.emailPlaceholder')"
                    value="{{ old('email') ? old('email') : $user->email }}" />
                <aside class="addon @error('email') invalid @enderror"><x-icon name="mail" size="2" /></aside>
            </div>
            @error('email')
            <div class="error">
                <ul class="errors">
                    <li><x-icon name="warning" size="2" /> {{ $message }}</li>
                </ul>
            </div>
            @enderror
        </div>
        <div class="form-row has-divider">
            <div class="label">
                <label for="role">@lang("admin.user.data.roleLabel")</label>
            </div>
            <div class="input">
                <select
                    name="role"
                    id="role"
                    class="form-select">
                    <option value="">@lang("admin.users.filter.selectEmptyOption")</option>
                    <option value="admin"{{ $user->role == 'admin' ? ' selected' : '' }}>admin</option>
                    <option value="mod"{{ $user->role == 'mod' ? ' selected' : '' }}>mod</option>
                    <option value="user"{{ $user->role == 'user' ? ' selected' : '' }}>user</option>
                </select>
                <aside class="addon"><x-icon name="group" size="2" /></aside>
            </div>
        </div>
        <div class="text-row has-divider">
            @lang('admin.user.data.createdAt'):
            {{ \Carbon\Carbon::parse($user->created_at)->format('d.m.Y H:i:s') }}<br />
            @lang('admin.user.data.updatedAt'):
            {{ \Carbon\Carbon::parse($user->updated_at)->format('d.m.Y H:i:s') }}
        </div>
        <div class="text-row has-divider">
            @lang('admin.user.data.verifiedAt'):
            @if(!$user->email_verified_at)
                &nbsp;
                <span class="symbol inline error">
                    <x-icon name="cancel" size="2" />
                </span>
            @else
                {{ \Carbon\Carbon::parse($user->email_verified_at)->format('d.m.Y H:i:s') }}
            @endif
        </div>
        <div class="form-row">
            <div class="input button">
                <button class="app-btn app-btn--submit both" style="margin-left: auto;">
                    <x-icon name="edit" size="2" />
                    <span>
                        @lang('admin.user.data.submitChanges')
                        <x-loading size="24" />
                    </span>
                </button>
            </div>
        </div>
    </form>


    @if(count($players) > 0)
        <div class="suspensions">
            <header class="suspensions__title">
                <h1>@lang('admin.user.players.sectionTitle')</h1>
            </header>
            <table class="suspensions__table">
                <thead>
                <tr>
                    <th>@lang('admin.user.players.game')</th>
                    <th>@lang('admin.user.players.player')</th>
                    <th>@lang('admin.user.players.gameStart')</th>
                    <th>@lang('admin.user.players.gameActive')</th>
                    <th>@lang('admin.user.players.gameEnd')</th>
                    <th>@lang('admin.user.players.gameDetails')</th>
                </tr>
                </thead>
                <tbody>
                @foreach($players as $player)
                    <tr>
                        <td>g{{ $player->game->number }}</td>
                        <td>[{{ $player->ticker }}] {{ $player->name }}</td>
                        <td>
                            <time
                                datetime="{{ \Carbon\Carbon::parse($player->game->start_date)->format('d.m.Y H:i:s') }}"
                                title="{{ \Carbon\Carbon::parse($player->game->start_date)->format('d.m.Y H:i:s') }}">
                                {{ \Carbon\Carbon::parse($player->game->start_date)->format('d.m.Y H:i:s') }}
                                ({{ \Carbon\Carbon::parse($player->game->start_date)->diffForHumans() }})
                            </time>
                        </td>
                        <td>
                            @if($player->game->active)
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
                            @if($player->game->finished)
                                <span class="symbol success">
                                    <x-icon name="done" size="2" />
                                </span>
                            @else
                                <span class="symbol error">
                                    <x-icon name="cancel" size="2" />
                                </span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <a class="app-btn both" href="{{ route('game-details', ['game' => $player->game->id]) }}" style="display: inline-flex;">
                                <x-icon name="search" size="2" />
                                <span>
                                    @lang('admin.user.players.gameDetails')
                                </span>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif


    @if(count($user->suspensions) !== 0)
        <div class="suspensions">
            <header class="suspensions__title">
                <h1>
                    @lang('admin.user.suspensions.sectionTitle')
                    @if($user->isSuspended())
                        <div class="suspensions__suspended">
                            <x-icon name="warning" size="2" />
                            User is suspended
                        </div>
                    @endif
                </h1>
            </header>
            <div class="suspensions__intro">@lang('admin.user.suspensions.intro')</div>
            <table class="suspensions__table">
                <thead>
                <tr>
                    <th>@lang('admin.user.suspensions.issued')</th>
                    <th>@lang('admin.user.suspensions.until')</th>
                    <th>@lang('admin.user.suspensions.issuedBy')</th>
                    <th>@lang('admin.user.suspensions.reason')</th>
                    @if(Auth::user()->isAdmin())
                        <th>@lang('admin.user.suspensions.delete')</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                    @foreach($user->suspensions as $suspension)
                        <tr class="{{ $suspension->isActive() ? 'active' : '' }}">
                            <td>{{ \Carbon\Carbon::parse($suspension->created_at)->format('d.m.Y H:i:s') }}</td>
                            <td>{{ \Carbon\Carbon::parse($suspension->until)->format('d.m.Y H:i:s') }}</td>
                            <td>{{ $suspension->issuer_id }}</td>
                            <td>{{ $suspension->issuer_reason }}</td>
                            @if(Auth::user()->isAdmin())
                                <td>
                                    @if($suspension->isActive())
                                        <button class="app-btn small app-btn--submit" data-modal="delete--{{ $suspension->id }}">
                                            <x-icon name="delete" size="2" />
                                            <span>
                                                <x-loading size="24" />
                                            </span>
                                        </button>
                                        <x-modal :title="__('admin.user.suspensions.deleteModalTitle')" ref-id="delete--{{ $suspension->id }}">
                                            @lang('admin.user.suspensions.deleteModalText')
                                            <div class="app-form in-section" action="" METHOD="POST">
                                                <div class="form-row">
                                                    <div class="input input--justify">
                                                        <button class="app-btn both" data-cancel>
                                                            <x-icon name="cancel" size="2" />
                                                            <span>
                                                                @lang('admin.user.suspensions.deleteModalCancel')
                                                            </span>
                                                        </button>
                                                        &nbsp;
                                                        <form
                                                            action="{{ route('delete-suspension', ['suspension' => $suspension->id]) }}"
                                                            METHOD="POST">
                                                            @csrf
                                                            <button class="app-btn app-btn--submit primary both">
                                                                <x-icon name="warning" size="2" />
                                                                <span>
                                                                    @lang('admin.user.suspensions.deleteModalSubmit')
                                                                    <x-loading size="24" />
                                                                </span>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </x-modal>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if(count($userReports) !== 0)
        <div class="suspensions">
            <header class="suspensions__title">
                <h1>@lang('admin.user.reporteeReports.sectionTitle')</h1>
            </header>
            <table class="suspensions__table">
                <thead>
                <tr>
                    <th>@lang('admin.user.reporteeReports.created')</th>
                    <th>@lang('admin.user.reporteeReports.game')</th>
                    <th>@lang('admin.user.reporteeReports.reporter')</th>
                    <th>@lang('admin.user.reporteeReports.comment')</th>
                    <th>@lang('admin.user.reporteeReports.resolved')</th>
                    <th>@lang('admin.user.reporteeReports.details')</th>
                </tr>
                </thead>
                <tbody>
                @foreach($userReports as $report)
                    <tr>
                        <td>
                            <time
                                datetime="{{ \Carbon\Carbon::parse($report->created_at)->format('d.m.Y H:i:s') }}"
                                title="{{ \Carbon\Carbon::parse($report->created_at)->format('d.m.Y H:i:s') }}">
                                {{ \Carbon\Carbon::parse($report->created_at)->format('d.m.Y H:i:s') }}
                                ({{ \Carbon\Carbon::parse($report->created_at)->diffForHumans() }})
                            </time>
                        </td>
                        <td>g{{$report->game->number}}</td>
                        <td>
                            [{{$report->reporter->ticker}}]
                        </td>
                        <td>{{substr($report->comment, 0, 20)}}</td>
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
                        <td style="text-align: right;">
                            <a class="app-btn both" href="{{ route('report', ['report' => $report->id]) }}" style="display: inline-flex;">
                                <x-icon name="search" size="2" />
                                <span>
                                    @lang('admin.user.reporteeReports.details')
                                </span>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if(count($userReportedReports) !== 0)
        <div class="suspensions">
            <header class="suspensions__title">
                <h1>@lang('admin.user.reporterReports.sectionTitle')</h1>
            </header>
            <table class="suspensions__table">
                <thead>
                <tr>
                    <th>@lang('admin.user.reporterReports.created')</th>
                    <th>@lang('admin.user.reporterReports.game')</th>
                    <th>@lang('admin.user.reporterReports.reportee')</th>
                    <th>@lang('admin.user.reporterReports.comment')</th>
                    <th>@lang('admin.user.reporterReports.resolved')</th>
                    <th>@lang('admin.user.reporterReports.details')</th>
                </tr>
                </thead>
                <tbody>
                @foreach($userReportedReports as $report)
                    <tr>
                        <td>
                            <time
                                datetime="{{ \Carbon\Carbon::parse($report->created_at)->format('d.m.Y H:i:s') }}"
                                title="{{ \Carbon\Carbon::parse($report->created_at)->format('d.m.Y H:i:s') }}">
                                {{ \Carbon\Carbon::parse($report->created_at)->format('d.m.Y H:i:s') }}
                                ({{ \Carbon\Carbon::parse($report->created_at)->diffForHumans() }})
                            </time>
                        </td>
                        <td>g{{$report->game->number}}</td>
                        <td>
                            [{{$report->reportee->ticker}}]
                        </td>
                        <td>{{substr($report->comment, 0, 20)}}</td>
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
                        <td style="text-align: right;">
                            <a class="app-btn both" href="{{ route('report', ['report' => $report->id]) }}" style="display: inline-flex;">
                                <x-icon name="search" size="2" />
                                <span>
                                    @lang('admin.user.reporterReports.details')
                                </span>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <form
        id="suspensionForm"
        class="app-form less-margins"
        action="{{ route('suspend-user', ['user' => $user->id]) }}#suspensionForm"
        METHOD="POST">
        @csrf
        <div class="headline" role="heading">
            <span>@lang('admin.user.suspensions.newTitle')</span>
        </div>
        <div class="intro">@lang('admin.user.suspensions.newSubtitle')</div>
        <div class="form-row has-divider">
            <div class="label">
                <label for="duration">@lang("admin.user.suspensions.durationLabel")</label>
            </div>
            <div class="input">
                <select
                    name="duration"
                    id="duration"
                    class="form-select @error('duration') invalid @enderror"
                    required
                    aria-required="true">
                    <option value="">@lang("admin.user.suspensions.selectEmptyOption")</option>
                    <option value="1"{{ old('duration') === '1' ? ' selected' : '' }}>@lang("admin.user.suspensions.duration1")</option>
                    <option value="3"{{ old('duration') === '3' ? ' selected' : '' }}>@lang("admin.user.suspensions.duration3")</option>
                    <option value="7"{{ old('duration') === '7' ? ' selected' : '' }}>@lang("admin.user.suspensions.duration7")</option>
                    <option value="14"{{ old('duration') === '14' ? ' selected' : '' }}>@lang("admin.user.suspensions.duration14")</option>
                    <option value="30"{{ old('duration') === '30' ? ' selected' : '' }}>@lang("admin.user.suspensions.duration30")</option>
                    <option value="99"{{ old('duration') === '99' ? ' selected' : '' }}>@lang("admin.user.suspensions.duration99")</option>
                </select>
                <aside class="addon @error('duration') invalid @enderror"><x-icon name="turn" size="2" /></aside>
            </div>
            @error('duration')
            <div class="error">
                <ul class="errors">
                    <li><x-icon name="warning" size="2" /> {{ $message }}</li>
                </ul>
            </div>
            @enderror
        </div>
        <div class="form-row has-divider">
            <div class="label">
                <label for="reason">
                    @lang("admin.user.suspensions.reasonLabel")
                    &nbsp;<img src="/assets/images/flags/{{ $user->locale }}.svg" width="30" height="15" />
                </label>
            </div>
            <div class="input">
                <input
                    type="text"
                    name="reason"
                    id="reason"
                    class="form-control @error('reason') invalid @enderror"
                    placeholder="@lang('admin.user.suspensions.reasonPlaceholder')"
                    value="{{ old('reason') ?? '' }}"
                    maxlength="200"
                    required
                    aria-required="true" />
                <aside class="addon @error('reason') invalid @enderror"><x-icon name="warning" size="2" /></aside>
            </div>
            @error('reason')
            <div class="error">
                <ul class="errors">
                    <li><x-icon name="warning" size="2" /> {{ $message }}</li>
                </ul>
            </div>
            @enderror
        </div>
        <div class="form-row">
            <div class="input button">
                <button class="app-btn app-btn--submit both" style="margin-left: auto;">
                    <x-icon name="warning" size="2" />
                    <span>
                        @lang('admin.user.suspensions.submit')
                        <x-loading size="24" />
                    </span>
                </button>
            </div>
        </div>
    </form>

    <div class="sessions">
        <header><h1>@lang('admin.user.session.sectionTitle')</h1></header>
        <ul class="sessions__list">
            @if(count($sessions) > 0)
                @foreach($sessions as $session)
                    <li class="session">
                        @if ($session->agent["is_desktop"])
                            <x-icon name="desktop" size="2" />
                        @else
                            <x-icon name="mobile" size="2" />
                        @endif
                        <div class="session__details">
                            {{ $session->agent["platform"] }} - {{ $session->agent["browser"] }}
                            @if ($session->is_current_device)
                                <cite>@lang('admin.user.session.thisDevice')</cite>
                            @else
                                <small>
                                    @lang('admin.user.session.lastActive')
                                    {{ \Carbon\Carbon::createFromTimestamp($session->last_active)->diffForHumans() }}
                                </small>
                            @endif
                        </div>
                        <div class="session__ip">
                            {{ $session->ip_address }}
                        </div>
                    </li>
                @endforeach
            @else
                <li class="session">
                    @lang('admin.user.session.none')
                </li>
            @endif
        </ul>
    </div>

@endsection
