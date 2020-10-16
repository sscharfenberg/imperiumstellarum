@extends('layouts.app')

@section('content')

    <header class="page-head">
        <nav class="page-head__breadcrumbs">
            <a class="page-head__crumb" href="{{ route('welcome') }}" aria-label="@lang('app.home.navTitle')">
                <x-icon name="home" size="2" />
            </a>
            <a class="page-head__crumb" href="/forgot-password" aria-label="@lang('app.forgot.title')">
                @lang('app.forgot.navTitle')
            </a>
        </nav>
        <h1 class="page-head__title">@lang('app.forgot.title')</h1>
    </header>

    <form class="app-form narrow has-headline" action="/forgot-password" METHOD="POST">
        @csrf

        <div class="headline" role="heading">
            <div
                data-spectral
                aria-label="@lang('game.empire.star.spectralType')"
                role="presentation"></div>
            <span>@lang('app.forgot.headline')</span>
        </div>
        <div class="intro">
            @lang('app.forgot.description')
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
        </div>

        <div class="form-row">
            <div class="input input--justify no-label">
                <button class="app-btn app-btn--submit both primary">
                    <x-icon name="mail" size="2" />
                    <span>
                        @lang('app.forgot.submit')
                        <x-loading size="24" />
                    </span>
                </button>
            </div>
        </div>

    </form>


        <form action="/forgot-password" METHOD="POST">
        @csrf
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

        @error('email')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror

        {{ $errors }}

        <hr />
        <button>Submit</button>

        <hr />
    </form>

@endsection
