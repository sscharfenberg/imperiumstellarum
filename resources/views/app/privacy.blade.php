@extends('layouts.app')

@section('content')

    <header class="page-head">
        <nav class="page-head__breadcrumbs">
            <a class="page-head__crumb" href="{{ route('welcome') }}" aria-label="@lang('app.home.navTitle')">
                <x-icon name="home" size="2" />
            </a>
            <a class="page-head__crumb" href="{{ route('privacy') }}" aria-label="@lang('app.privacy.title')">
                @lang('app.privacy.navTitle')
            </a>
        </nav>
        <h1 class="page-head__title">@lang('app.privacy.title')</h1>
        <aside class="page-head__description">
            @lang('app.privacy.description')
        </aside>
    </header>

    <x-text-section :hdl="__('app.privacy.whatData.title')">
        <p>@lang('app.privacy.whatData.paragraph1')</p>
    </x-text-section>

    <x-text-section :hdl="__('app.privacy.howData.title')">
        <p>@lang('app.privacy.howData.paragraph1')</p>
    </x-text-section>

    <x-text-section :hdl="__('app.privacy.dataUse.title')">
        <p>@lang('app.privacy.dataUse.paragraph1')</p>
        <p>@lang('app.privacy.dataUse.paragraph2')</p>
        <p>@lang('app.privacy.dataUse.paragraph3')</p>
    </x-text-section>

    <x-text-section :hdl="__('app.privacy.dataStorage.title')">
        <p>@lang('app.privacy.dataStorage.paragraph1')</p>
    </x-text-section>

    <x-text-section :hdl="__('app.privacy.dataProtection.title')">
        <p>@lang('app.privacy.dataProtection.paragraph1')</p>
        <ul>
            <li>
                <strong>@lang('app.privacy.dataProtection.bullet1.title')</strong> -
                @lang('app.privacy.dataProtection.bullet1.point')
            </li>
            <li>
                <strong>@lang('app.privacy.dataProtection.bullet2.title')</strong> -
                @lang('app.privacy.dataProtection.bullet2.point')
            </li>
            <li>
                <strong>@lang('app.privacy.dataProtection.bullet3.title')</strong> -
                @lang('app.privacy.dataProtection.bullet3.point')
            </li>
            <li>
                <strong>@lang('app.privacy.dataProtection.bullet4.title')</strong> -
                @lang('app.privacy.dataProtection.bullet4.point')
            </li>
            <li>
                <strong>@lang('app.privacy.dataProtection.bullet5.title')</strong> -
                @lang('app.privacy.dataProtection.bullet5.point')
            </li>
            <li>
                <strong>@lang('app.privacy.dataProtection.bullet6.title')</strong> -
                @lang('app.privacy.dataProtection.bullet6.point')
            </li>
        </ul>
        <p>
            @lang('app.privacy.dataProtection.paragraph2')
            <a class="text-link" href="mailto:ash@imperiumstellarum.io"><x-icon name="mail" size="2" />ash@imperiumstellarum.io</a>.
        </p>
    </x-text-section>

    <x-text-section :hdl="__('app.privacy.cookies.title')">
        <p>@lang('app.privacy.cookies.paragraph1')</p>
        <p>@lang('app.privacy.cookies.paragraph2')</p>
    </x-text-section>

    <x-text-section :hdl="__('app.privacy.cookiesHow.title')">
        <p>@lang('app.privacy.cookiesHow.paragraph1')</p>
        <ul>
            <li>@lang('app.privacy.cookiesHow.bullet1')</li>
            <li>@lang('app.privacy.cookiesHow.bullet2')</li>
            <li>@lang('app.privacy.cookiesHow.bullet3')</li>
        </ul>
    </x-text-section>

    <x-text-section :hdl="__('app.privacy.cookiesTypes.title')">
        <p>@lang('app.privacy.cookiesTypes.paragraph1')</p>
        <p>@lang('app.privacy.cookiesTypes.paragraph2')</p>
    </x-text-section>

    <x-text-section :hdl="__('app.privacy.cookiesManage.title')">
        <p>@lang('app.privacy.cookiesManage.paragraph1')</p>
    </x-text-section>

    <x-text-section :hdl="__('app.privacy.cookiesOther.title')">
        <p>@lang('app.privacy.cookiesOther.paragraph1')</p>
    </x-text-section>

    <x-text-section :hdl="__('app.privacy.cookiesChanges.title')">
        <p>@lang('app.privacy.cookiesChanges.paragraph1')</p>
    </x-text-section>

    <x-text-section :hdl="__('app.privacy.contact.title')">
        <p>@lang('app.privacy.contact.paragraph1')</p>
        <p>
            @lang('app.privacy.contact.paragraph2')
            <a class="text-link" href="mailto:ash@imperiumstellarum.io"><x-icon name="mail" size="2" />ash@imperiumstellarum.io</a>.
        </p>
    </x-text-section>

@endsection
