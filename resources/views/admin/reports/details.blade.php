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
            <a
                class="page-head__crumb"
                href="{{ route('report', ['report' => $report->id]) }}"
                aria-label="@lang('admin.report.title')">
                @lang('admin.report.navTitle')
            </a>
        </nav>
        <h1 class="page-head__title">@lang('admin.report.title', ['ticker' => $report->reportee->ticker])</h1>
    </header>

    <div class="app-form has-headline">

        <div class="headline" role="heading">
            <div
                data-spectral
                aria-label="@lang('game.empire.star.spectralType')"
                role="presentation"></div>
            <span>@lang('admin.report.data.headline')</span>
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label>@lang('admin.report.data.game')</label>
            </div>
            <div class="input">
                g{{ $report->game->number }}
                <a class="app-btn both" href="{{ route('game-details', ['game' => $report->game_id]) }}" style="margin-left: auto;">
                    <x-icon name="games" size="2" />
                    <span>
                        @lang('admin.report.data.gameDetails', ['game' => $report->game->number])
                    </span>
                </a>
            </div>
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label>@lang('admin.report.data.reportedAt')</label>
            </div>
            <div class="input">
                <time
                    datetime="{{ \Carbon\Carbon::parse($report->created_at)->format('d.m.Y H:i:s') }}"
                    title="{{ \Carbon\Carbon::parse($report->created_at)->format('d.m.Y H:i:s') }}">
                    {{ \Carbon\Carbon::parse($report->created_at)->format('d.m.Y H:i:s') }}
                    ({{ \Carbon\Carbon::parse($report->created_at)->diffForHumans() }})
                </time>
            </div>
        </div>

        <div class="form-row">
            <div class="label">
                <label>@lang('admin.report.data.reporter')</label>
            </div>
            <div class="input">
                [{{ $report->reporter->ticker }}]
                {{ $report->reporter->name }}
            </div>
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label>@lang('admin.report.data.reporterComment')</label>
            </div>
            <div class="input">
                <div class="app-html">
                    {!! nl2br(htmlspecialchars($report->comment)) !!}
                </div>
            </div>
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label>@lang('admin.report.data.reportee')</label>
            </div>
            <div class="input">
                [{{ $report->reportee->ticker }}]
                {{ $report->reportee->name }}
            </div>
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label>@lang('admin.report.data.reporteeUser')</label>
            </div>
            <div class="input">
                ID: {{ $report->reportee->user->id }}
                &nbsp;
                <a class="text-link" href="mailto:{{ $report->reportee->user->email }}">&lt;{{ $report->reportee->user->email }}&gt;</a>
                <a class="app-btn both" href="{{ route('user-details', ['user' => $report->reportee->user->id]) }}" style="margin-left: auto;">
                    <x-icon name="user" size="2" />
                    <span>
                        @lang('admin.report.data.reporteeUserDetails')
                    </span>
                </a>
            </div>
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label>@lang('admin.report.data.resolved')</label>
            </div>
            <div class="input">
                @if($report->resolved_admin !== null)
                    <span class="symbol success">
                        <x-icon name="done" size="2" />
                    </span>
                    <span>&nbsp; Admin ID: {{ $report->resolved_admin }}</span>
                @else
                    <span class="symbol error">
                        <x-icon name="cancel" size="2" />
                    </span>
                @endif
            </div>
        </div>

        @if($report->resolved_admin !== null)
            <div class="form-row has-divider">
                <div class="label">
                    <label>@lang('admin.report.data.reporterMessage', ['ticker' => $report->reporter->ticker])</label>
                </div>
                <div class="input">
                    {!! nl2br(htmlspecialchars($report->reporterMessage->body)) !!}
                </div>
            </div>

            @if($report->admin_reportee_message_id !== null)
                <div class="form-row has-divider">
                    <div class="label">
                        <label>@lang('admin.report.data.reporteeMessage', ['ticker' => $report->reportee->ticker])</label>
                    </div>
                    <div class="input">
                        {!! nl2br(htmlspecialchars($report->reporteeMessage->body)) !!}
                    </div>
                </div>
            @endif

            @if($report->suspension_duration !== null)
                <div class="form-row has-divider">
                    <div class="label">
                        <label>@lang('admin.report.data.reporteeSuspensionDuration', ['ticker' => $report->reportee->ticker])</label>
                    </div>
                    <div class="input">
                        @lang('admin.user.suspensions.duration'.$report->suspension_duration)
                    </div>
                </div>
            @endif
        @endif

    </div>

    <div class="app-form has-headline">

        <div class="headline" role="heading">
            <div
                data-spectral
                aria-label="@lang('game.empire.star.spectralType')"
                role="presentation"></div>
            <span>@lang('admin.report.message.headline')</span>
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label>ID</label>
            </div>
            <div class="input">
                {{ $report->message->id }}
            </div>
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label>@lang('admin.report.message.sentAt')</label>
            </div>
            <div class="input">
                <time
                    datetime="{{ \Carbon\Carbon::parse($report->message->created_at)->format('d.m.Y H:i:s') }}"
                    title="{{ \Carbon\Carbon::parse($report->message->created_at)->format('d.m.Y H:i:s') }}">
                    {{ \Carbon\Carbon::parse($report->message->created_at)->format('d.m.Y H:i:s') }}
                    ({{ \Carbon\Carbon::parse($report->message->created_at)->diffForHumans() }})
                </time>
            </div>
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label>@lang('admin.report.message.sentBy')</label>
            </div>
            <div class="input">
                [{{ $report->message->sender->ticker }}]
                {{ $report->message->sender->name }}
            </div>
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label>@lang('admin.report.message.recipients')</label>
            </div>
            <div class="input" style="display: block;">
                @foreach($messageRecipients as $index => $recipient)
                    @if($recipient === $report->reporter->ticker)
                        <strong>[{{$recipient}}]</strong>@if($index + 1 !== count($messageRecipients)), @endif
                    @else
                        [{{$recipient}}]@if($index + 1 !== count($messageRecipients)), @endif
                    @endif
                @endforeach
            </div>
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label>@lang('admin.report.message.subject')</label>
            </div>
            <div class="input">
                {{ $report->message->subject }}
            </div>
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label>@lang('admin.report.message.body')</label>
            </div>
            <div class="input">
                <div class="app-html">
                    {!! nl2br(htmlspecialchars($report->message->body)) !!}
                </div>
            </div>
        </div>

    </div>

    @if($report->resolved_admin === null)
        <form
            class="app-form"
            action="{{ route('report-resolve', ['report' => $report->id]) }}"
            METHOD="POST">
            @csrf
            <div class="headline" role="heading">
                <span>@lang('admin.report.resolve.headline')</span>
            </div>
            <div class="intro">@lang('admin.report.resolve.explanation')</div>

            <div class="form-row has-divider">
                <div class="label">
                    <label for="duration">@lang("admin.report.resolve.suspension", ['ticker' => $report->reportee->ticker])</label>
                </div>
                <div class="input">
                    <select
                        name="duration"
                        id="duration"
                        class="form-select @error('duration') invalid @enderror">
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
                <div class="descr">@lang('admin.report.resolve.suspensionExplanation', ['ticker' => $report->reportee->ticker])</div>
            </div>

            <div class="form-row has-divider">
                <div class="label">
                    <label for="reporteeMsg">
                        @lang("admin.report.resolve.reporteeMsgLabel", ['ticker' => $report->reportee->ticker])
                        &nbsp;<img src="/assets/images/flags/{{ $report->reportee->user->locale }}.svg" width="30" height="15" />
                    </label>
                </div>
                <div class="input">
                    <textarea
                        name="reporteeMsg"
                        id="reporteeMsg"
                        class="form-control @error('reporteeMsg') invalid @enderror"
                        style="height: 90px;">{{ old('reporteeMsg') ?? '' }}</textarea>
                </div>
                @error('reporteeMsg')
                <div class="error">
                    <ul class="errors">
                        <li><x-icon name="warning" size="2" /> {{ $message }}</li>
                    </ul>
                </div>
                @enderror
                <div class="descr">
                    @lang('admin.report.resolve.reporteeMsgExplanation', ['ticker' => $report->reportee->ticker])
                    @lang('admin.report.resolve.msgLength', [
                        'min' => config('rules.reports.reportMessage.min'),
                        'max' => config('rules.reports.reportMessage.max')
                    ])
                </div>
            </div>

            <div class="form-row has-divider">
                <div class="label">
                    <label for="reporterMsg">
                        @lang("admin.report.resolve.reporterMsgLabel", ['ticker' => $report->reporter->ticker])
                        &nbsp;<img src="/assets/images/flags/{{ $report->reporter->user->locale }}.svg" width="30" height="15" />
                    </label>
                </div>
                <div class="input">
                    <textarea
                        name="reporterMsg"
                        id="reporterMsg"
                        class="form-control @error('reporterMsg') invalid @enderror"
                        style="height: 90px;"
                        required
                        aria-required="true">{{ old('reporterMsg') ?? '' }}</textarea>
                </div>
                @error('reporterMsg')
                <div class="error">
                    <ul class="errors">
                        <li><x-icon name="warning" size="2" /> {{ $message }}</li>
                    </ul>
                </div>
                @enderror
                <div class="descr">
                    @lang('admin.report.resolve.reporterMsgExplanation', ['ticker' => $report->reporter->ticker])
                    @lang('admin.report.resolve.msgLength', [
                        'min' => config('rules.reports.reportMessage.min'),
                        'max' => config('rules.reports.reportMessage.max')
                    ])
                </div>
            </div>

            <div class="form-row">
                <div class="input button">
                    <button class="app-btn app-btn--submit both" style="margin-left: auto;">
                        <x-icon name="save" size="2" />
                        <span>
                            @lang('admin.report.resolve.submit')
                            <x-loading size="24" />
                        </span>
                    </button>
                </div>
            </div>

        </form>
    @endif

@endsection
