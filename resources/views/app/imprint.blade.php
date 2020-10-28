@extends('layouts.app')

@section('content')

    <header class="page-head">
        <nav class="page-head__breadcrumbs">
            <a class="page-head__crumb" href="{{ route('welcome') }}" aria-label="@lang('app.home.navTitle')">
                <x-icon name="home" size="2" />
            </a>
            <a class="page-head__crumb" href="{{ route('imprint') }}" aria-label="@lang('app.imprint.title')">
                @lang('app.imprint.navTitle')
            </a>
        </nav>
        <h1 class="page-head__title">@lang('app.imprint.title')</h1>
    </header>

    <x-text-section :hdl="__('app.imprint.navTitle')">
        <p>@lang('app.imprint.paragraph1')</p>
        <p>Sven Scharfenberg<br />Blaue Str.9<br />21726 Oldendorf</p>
        <p><strong>@lang('app.imprint.paragraph2')</strong>:</p>
        <p>Sven Scharfenberg</p>
        <p><strong>@lang('app.imprint.contact')</strong>:</p>
        <p>Telefon 04144 / 7774<br />ash@imperiumstellarum.io</p>
    </x-text-section>

    <x-text-section :hdl="__('app.imprint.attribution')">
        <p>@lang('app.imprint.paragraph3')</p>
        <a class="text-link" href="https://github.com/sscharfenberg/imperiumstellarum/ATTRIBUTION.md" rel="noopener"><x-icon name="link" size="2" /> Attribution</a>
    </x-text-section>

    <x-text-section :hdl="__('app.imprint.disclaimer')">
        <p><strong>@lang('app.imprint.headline4')</strong></p>
        <p>@lang('app.imprint.paragraph4')</p>
        <p><strong>@lang('app.imprint.headline5')</strong></p>
        <p>@lang('app.imprint.paragraph5')</p>
    </x-text-section>

    <p>@lang('app.imprint.generatedby')</p>

@endsection
