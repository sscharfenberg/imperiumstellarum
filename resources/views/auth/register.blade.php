@extends('layouts.app')

@section('content')

    <header class="page-head">
        <nav class="page-head__breadcrumbs">
            <a class="page-head__crumb" href="{{ route('welcome') }}" aria-label="@lang('app.home.navTitle')">
                <x-icon name="home" size="2" />
            </a>
            <a class="page-head__crumb" href="{{ route('register') }}" aria-label="@lang('app.register.title')">
                @lang('app.register.navTitle')
            </a>
        </nav>
        <h1 class="page-head__title">@lang('app.register.title')</h1>
        <aside class="page-head__description">
            @lang('app.register.description')
        </aside>
    </header>

    <form class="app-form narrow has-headline" action="{{ route('register') }}" METHOD="POST">
        @csrf
        <input type="hidden" name="locale" value="{{ session('locale') }}" />
        <div class="headline" role="heading">
            <div
                data-spectral
                aria-label="@lang('game.empire.star.spectralType')"
                role="presentation"></div>
            <span>@lang('app.register.headline')</span>
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label for="email">@lang("app.register.emailLabel")</label>
            </div>
            <div class="input">
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="form-control @error('email') invalid @enderror"
                    required
                    aria-required="true"
                    maxlength="120"
                    placeholder="@lang('app.register.emailPlaceholder')"
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
            <div class="descr">@lang("app.register.emailDescription")</div>
        </div>

        <div class="form-row">
            <div class="label">
                <label for="password">@lang("app.register.passwordLabel")</label>
            </div>
            <div class="input">
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="form-control @error('password') invalid @enderror"
                    required
                    aria-required="true"
                    placeholder="@lang('app.register.passwordPlaceholder')"
                />
                <aside class="addon @error('password') invalid @enderror"><x-icon name="lock" size="2" /></aside>
            </div>
            <div class="meter">
                <x-password-strength />
            </div>
            @error('password')
            <div class="error">
                <ul class="errors">
                    <li><x-icon name="warning" size="2" /> {{ $message }}</li>
                </ul>
            </div>
            @enderror
            <div class="descr">@lang("app.register.passwordDescription")</div>
        </div>

        <div class="form-row has-divider">
            <div class="label">
                <label for="password_confirmation">@lang("app.register.repeatPasswordLabel")</label>
            </div>
            <div class="input">
                <input
                    type="password"
                    name="password_confirmation"
                    id="password_confirmation"
                    class="form-control"
                    required
                    aria-required="true"
                    placeholder="@lang('app.register.repeatPasswordPlaceholder')"
                />
                <aside class="addon"><x-icon name="lock" size="2" /></aside>
            </div>
            <div class="descr">@lang("app.register.repeatPasswordDescription")</div>
        </div>

        <div class="form-row has-divider">
            <div class="input no-label">
                <x-checkbox
                    selected="{{ old('confirmed') ? '1' : '0' }}"
                    ref-id="confirmed"
                    error="{{ $errors->has('confirmed') ? '1' : '0' }}">
                    @lang('app.register.confirmedLabel')
                </x-checkbox>
            </div>
            @error('confirmed')
            <div class="error">
                <ul class="errors">
                    <li><x-icon name="warning" size="2" /> {{ $message }}</li>
                </ul>
            </div>
            @enderror
            <div class="descr">
                <div class="links">
                    <a class="text-link" href="/">
                        @lang("app.privacy.navTitle")
                    </a>
                    -
                    <a class="text-link" href="/">
                        @lang("app.terms.navTitle")
                    </a>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="input no-label">
                <button class="app-btn app-btn--submit both primary">
                    <x-icon name="lightbulb" size="2" />
                    <span>
                        @lang('app.register.submit')
                        <x-loading size="24" />
                    </span>
                </button>
            </div>
        </div>

    </form>

@endsection
