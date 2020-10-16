@extends('layouts.app')

@section('content')

    <header class="page-head">
        <nav class="page-head__breadcrumbs">
            <a class="page-head__crumb" href="{{ route('welcome') }}" aria-label="@lang('app.home.navTitle')">
                <x-icon name="home" size="2" />
            </a>
            <a class="page-head__crumb" href="{{ route('login') }}" aria-label="@lang('app.login.title')">
                @lang('app.login.navTitle')
            </a>
        </nav>
        <h1 class="page-head__title">@lang('app.login.title')</h1>
        <aside class="page-head__description">
            @lang('app.login.description')
        </aside>
    </header>

    <form class="app-form narrow has-headline" action="{{ route('login') }}" METHOD="POST">
        @csrf

        <div class="headline" role="heading">
            <div
                data-spectral
                aria-label="@lang('game.empire.star.spectralType')"
                role="presentation"></div>
            <span>@lang('app.login.headline')</span>
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label for="email">@lang("app.login.emailLabel")</label>
            </div>
            <div class="input">
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="form-control @error('email') invalid @enderror"
                    required
                    aria-required="true"
                    placeholder="@lang('app.login.emailPlaceholder')"
                    value="{{ old('email') }}" />
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
                <label for="password">@lang("app.login.passwordLabel")</label>
            </div>
            <div class="input">
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="form-control @error('email') invalid @enderror"
                    required
                    aria-required="true"
                    placeholder="@lang('app.login.passwordPlaceholder')"
                />
                <aside class="addon @error('email') invalid @enderror"><x-icon name="lock" size="2" /></aside>
            </div>
            @error('password')
            <div class="error">
                <ul class="errors">
                    <li><x-icon name="warning" size="2" /> {{ $message }}</li>
                </ul>
            </div>
            @enderror
        </div>

        <div class="form-row has-divider">
            <div class="input no-label">
                <x-checkbox
                    selected="{{ old('remember') ? '1' : '0' }}"
                    ref-id="remember"
                    error="{{ $errors->has('remember') ? '1' : '0' }}">
                    @lang('app.login.rememberLabel')
                </x-checkbox>
            </div>
            @error('remember')
            <div class="error">
                <ul class="errors">
                    <li><x-icon name="warning" size="2" /> {{ $message }}</li>
                </ul>
            </div>
            @enderror
        </div>

        <div class="form-row">
            <div class="input input--justify no-label">
                <button class="app-btn app-btn--submit both primary">
                    <x-icon name="key" size="2" />
                    <span>
                        @lang('app.login.submit')
                        <x-loading size="24" />
                    </span>
                </button>
                <a class="text-link" href="{{ route('password.request') }}">
                    @lang('app.forgot.title')
                </a>
            </div>
        </div>

    </form>

@endsection
