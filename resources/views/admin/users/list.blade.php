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
        </nav>
        <h1 class="page-head__title">@lang('admin.users.title')</h1>
    </header>

    <form action="{{ route('users') }}" METHOD="POST">
        @csrf

        <section class="app-section">
            <header class="app-section__head">
                <h1>@lang('admin.users.filter.sectionTitle')</h1>
                <p>@lang('admin.users.filter.sectionSubtitle')</p>
            </header>
            <div class="app-section__main">
                <div class="app-form in-section">
                    <div class="form-row has-divider">
                        <div class="label">
                            <label for="filterId">@lang("admin.users.filter.idLabel")</label>
                        </div>
                        <div class="input">
                            <input
                                type="number"
                                name="filterId"
                                id="filterId"
                                class="form-control"
                                value="{{ $filterId }}" />
                            <aside class="addon"><x-icon name="user" size="2" /></aside>
                        </div>
                    </div>

                    <div class="form-row has-divider">
                        <div class="label">
                            <label for="filterEmail">@lang("admin.users.filter.emailLabel")</label>
                        </div>
                        <div class="input">
                            <input
                                type="text"
                                name="filterEmail"
                                id="filterEmail"
                                class="form-control"
                                value="{{ $filterEmail }}" />
                            <aside class="addon"><x-icon name="mail" size="2" /></aside>
                        </div>
                    </div>

                    <div class="form-row has-divider">
                        <div class="label">
                            <label for="filterRole">@lang("admin.users.filter.role")</label>
                        </div>
                        <div class="input">
                            <select
                                name="filterRole"
                                id="filterRole"
                                class="form-select">
                                <option value="">@lang("admin.users.filter.selectEmptyOption")</option>
                                <option value="admin"{{ $filterRole == 'admin' ? ' selected' : '' }}>admin</option>
                                <option value="mod"{{ $filterRole == 'mod' ? ' selected' : '' }}>mod</option>
                                <option value="user"{{ $filterRole == 'user' ? ' selected' : '' }}>user</option>
                            </select>
                            <aside class="addon"><x-icon name="group" size="2" /></aside>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input button">
                            <button class="app-btn app-btn--submit both">
                                <x-icon name="search" size="2" />
                                <span>
                                @lang('admin.users.filter.submit')
                                    <x-loading size="24" />
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="pagination">
            <div class="pagination__meta">
                @lang('admin.users.pagination.num', ['users' => $users->count(), 'total' => $users->total() ]),
                @lang('admin.users.pagination.sort', ['sort' => __('admin.users.thead.'.$sortBy), 'dir' => __('admin.users.sort.'.$order) ])
            </div>
            <div class="pagination__perpage">
                @lang('admin.users.perPage.showing')
                <select name="perPage" data-perpage>
                    <option value="10"{{ $perPage == '10' ? ' selected' : '' }}>10</option>
                    <option value="20"{{ $perPage == '20' ? ' selected' : '' }}>20</option>
                    <option value="50"{{ $perPage == '50' ? ' selected' : '' }}>50</option>
                    <option value="100"{{ $perPage == '100' ? ' selected' : '' }}>100</option>
                </select>
                @lang('admin.users.perPage.results')
            </div>
            {{ $users->appends(['sortBy' => $sortBy, 'order' => $order, 'perPage' => $perPage, 'filterRole' => $filterRole, 'filterEmail' => $filterEmail])->links('shared.pagination') }}
        </div>

        <table class="admin-table" data-tr-href="/admin/user/">
            <thead>
            <tr>
                <th class="sortable{{ $sortBy == 'id' ? ' sorted' : '' }}">
                    @lang('admin.users.thead.id')
                    <div class="sort">
                        <input type="radio" name="sort" value="id--asc" id="sort_id_asc" {{ $sortBy == 'id' && $order == 'asc' ? 'checked' : '' }} />
                        <label class="asc" for="sort_id_asc"></label>
                        <input type="radio" name="sort" value="id--desc" id="sort_id_desc" {{ $sortBy == 'id' && $order == 'desc' ? 'checked' : '' }} />
                        <label class="desc" for="sort_id_desc"></label>
                    </div>
                </th>
                <th class="sortable{{ $sortBy == 'email' ? ' sorted' : '' }}">
                    @lang('admin.users.thead.email')
                    <div class="sort">
                        <input type="radio" name="sort" value="email--asc" id="sort_email_asc" {{ $sortBy == 'email' && $order == 'asc' ? 'checked' : '' }} />
                        <label class="asc" for="sort_email_asc"></label>
                        <input type="radio" name="sort" value="email--desc" id="sort_email_desc" {{ $sortBy == 'email' && $order == 'desc' ? 'checked' : '' }} />
                        <label class="desc" for="sort_email_desc"></label>
                    </div>
                </th>
                <th>
                    @lang('admin.users.thead.isSuspended')
                </th>
                <th class="sortable{{ $sortBy == 'email_verified_at' ? ' sorted' : '' }}">
                    @lang('admin.users.thead.email_verified_at')
                    <div class="sort">
                        <input type="radio" name="sort" value="email_verified_at--asc" id="sort_verified_at_asc" {{ $sortBy == 'email_verified_at' && $order == 'asc' ? 'checked' : '' }} />
                        <label class="asc" for="sort_verified_at_asc"></label>
                        <input type="radio" name="sort" value="email_verified_at--desc" id="sort_verified_at_desc" {{ $sortBy == 'email_verified_at' && $order == 'desc' ? 'checked' : '' }} />
                        <label class="desc" for="sort_verified_at_desc"></label>
                    </div>
                </th>
                <th class="sortable{{ $sortBy == 'locale' ? ' sorted' : '' }}">
                    @lang('admin.users.thead.locale')
                    <div class="sort">
                        <input type="radio" name="sort" value="locale--asc" id="sort_locale_asc" {{ $sortBy == 'locale' && $order == 'asc' ? 'checked' : '' }} />
                        <label class="asc" for="sort_locale_asc"></label>
                        <input type="radio" name="sort" value="locale--desc" id="sort_locale_desc" {{ $sortBy == 'locale' && $order == 'desc' ? 'checked' : '' }} />
                        <label class="desc" for="sort_locale_desc"></label>
                    </div>
                </th>
                <th class="sortable{{ $sortBy == 'role' ? ' sorted' : '' }}">
                    @lang('admin.users.thead.role')
                    <div class="sort">
                        <input type="radio" name="sort" value="role--asc" id="sort_role_asc" {{ $sortBy == 'role' && $order == 'asc' ? 'checked' : '' }} />
                        <label class="asc" for="sort_role_asc"></label>
                        <input type="radio" name="sort" value="role--desc" id="sort_role_desc" {{ $sortBy == 'role' && $order == 'desc' ? 'checked' : '' }} />
                        <label class="desc" for="sort_role_desc"></label>
                    </div>
                </th>
                <th class="sortable{{ $sortBy == 'game_mail_optin' ? ' sorted' : '' }}">
                    @lang('admin.users.thead.game_mail_optin')
                    <div class="sort">
                        <input type="radio" name="sort" value="game_mail_optin--asc" id="sort_game_mail_optin_asc" {{ $sortBy == 'game_mail_optin' && $order == 'asc' ? 'checked' : '' }} />
                        <label class="asc" for="sort_game_mail_optin_asc"></label>
                        <input type="radio" name="sort" value="game_mail_optin--desc" id="sort_game_mail_optin_desc" {{ $sortBy == 'game_mail_optin' && $order == 'desc' ? 'checked' : '' }} />
                        <label class="desc" for="sort_game_mail_optin_desc"></label>
                    </div>
                </th>
                <th class="sortable{{ $sortBy == 'created_at' ? ' sorted' : '' }}">
                    @lang('admin.users.thead.created_at')
                    <div class="sort">
                        <input type="radio" name="sort" value="created_at--asc" id="sort_created_at_asc" {{ $sortBy == 'created_at' && $order == 'asc' ? 'checked' : '' }} />
                        <label class="asc" for="sort_created_at_asc"></label>
                        <input type="radio" name="sort" value="created_at--desc" id="sort_created_at_desc" {{ $sortBy == 'created_at' && $order == 'desc' ? 'checked' : '' }} />
                        <label class="desc" for="sort_created_at_desc"></label>
                    </div>
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach ($users as $user)
                <tr data-id="{{ $user->id }}">
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->email }}</td>
                    <td class="suspension">
                        @if(in_array($user->id, $suspensions))
                            <x-icon name="warning" size="2" />
                        @endif
                    </td>
                    <td>
                        @if($user->email_verified_at)
                            <time class="symbol success"
                                  datetime="{{ \Carbon\Carbon::parse($user->email_verified_at)->format('d.m.Y H:i:s') }}"
                                  title="{{ \Carbon\Carbon::parse($user->email_verified_at)->format('d.m.Y H:i:s') }}">
                                <x-icon name="done" size="2" />
                            </time>
                        @else
                            <span class="symbol error">
                                <x-icon name="cancel" size="2" />
                            </span>
                        @endif
                    </td>
                    <td class="locale"><img src="/assets/images/flags/{{ $user->locale }}.svg" /></td>
                    <td>{{ $user->role }}</td>
                    <td>
                        @if($user->game_mail_optin)
                            <span class="symbol success">
                                <x-icon name="done" size="2" />
                            </span>
                        @else
                            <span class="symbol error">
                                <x-icon name="cancel" size="2" />
                            </span>
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d.m.Y H:i:s') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        @csrf
        <div class="pagination">
            <div class="pagination__meta">
                @lang('admin.users.pagination.num', ['users' => $users->count(), 'total' => $users->total() ]),
                @lang('admin.users.pagination.sort', ['sort' => __('admin.users.thead.'.$sortBy), 'dir' => __('admin.users.sort.'.$order) ])
            </div>
            {{ $users->appends(['sortBy' => $sortBy, 'order' => $order, 'perPage' => $perPage, 'filterRole' => $filterRole, 'filterEmail' => $filterEmail])->links('shared.pagination') }}
        </div>
    </form>

@endsection
