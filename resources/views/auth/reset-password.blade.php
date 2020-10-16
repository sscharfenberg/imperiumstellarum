@extends('layouts.app')

@section('content')

    <header class="page-head">
        <h1 class="page-head__title">@lang('app.reset.title')</h1>
    </header>

    <form class="app-form narrow has-headline" action="/reset-password" METHOD="POST">

        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <div class="headline" role="heading">
            <div
                data-spectral
                aria-label="@lang('game.empire.star.spectralType')"
                role="presentation"></div>
            <span>@lang('app.reset.headline')</span>
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
            <div class="descr">@lang("app.reset.emailDescription")</div>
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
                    class="@error('password') invalid @enderror"
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
                    required
                    aria-required="true"
                    placeholder="@lang('app.register.repeatPasswordPlaceholder')"
                />
                <aside class="addon"><x-icon name="lock" size="2" /></aside>
            </div>
            <div class="descr">@lang("app.register.repeatPasswordDescription")</div>
        </div>

        <div class="form-row">
            <div class="input no-label">
                <button class="app-btn app-btn--submit both primary">
                    <x-icon name="lock" size="2" />
                    <span>
                        @lang('app.reset.submit')
                        <x-loading size="24" />
                    </span>
                </button>
            </div>
        </div>

    </form>

@endsection
