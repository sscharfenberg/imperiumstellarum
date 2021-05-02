@extends('layouts.app')

@section('content')

    <header class="page-head">
        <nav class="page-head__breadcrumbs">
            <a class="page-head__crumb" href="{{ route('welcome') }}" aria-label="@lang('app.home.navTitle')">
                <x-icon name="home" size="2" />
            </a>
            <a class="page-head__crumb" href="{{ route('terms') }}" aria-label="@lang('app.terms.title')">
                @lang('app.terms.navTitle')
            </a>
        </nav>
        <h1 class="page-head__title">@lang('app.terms.title')</h1>
    </header>

    <p>@lang('app.terms.intro')</p>

    <x-text-section :hdl="__('app.terms.acceptance.title')">
        <p>@lang('app.terms.acceptance.paragraph1')</p>
        <p>@lang('app.terms.acceptance.paragraph2')</p>
        <p><a class="text-link" href="{{ route('privacy') }}"><x-icon name="mail" size="2" />@lang('app.privacy.title')</a></p>
    </x-text-section>

    <x-text-section :hdl="__('app.terms.warranty.title')">
        <p>@lang('app.terms.warranty.paragraph1')</p>
        <p>
            <a class="text-link" href="https://github.com/sscharfenberg/imperiumstellarum/graphs/contributors"><x-icon name="link" size="2" />@lang('app.terms.warranty.contributors')</a>
        </p>
    </x-text-section>

    <x-text-section :hdl="__('app.terms.termination.title')">
        <p>@lang('app.terms.termination.paragraph')</p>
    </x-text-section>

    <x-text-section :hdl="__('app.terms.communityGuidelines.title')">
        <p>@lang('app.terms.communityGuidelines.paragraph1')</p>
        <p>@lang('app.terms.communityGuidelines.paragraph2')</p>
        <p>@lang('app.terms.communityGuidelines.paragraph3')</p>
        <p>@lang('app.terms.communityGuidelines.paragraph4')</p>
        <p>@lang('app.terms.communityGuidelines.paragraph5')</p>
    </x-text-section>

    <x-text-section :hdl="__('app.terms.exploits.title')">
        <p>@lang('app.terms.exploits.paragraph1')</p>
        <p>@lang('app.terms.exploits.paragraph2')</p>
        <p>
            <a class="text-link" href="https://discuss.imperiumstellarum.io/index.php?/staff/"><x-icon name="link" size="2" />@lang('app.terms.exploits.staff')</a>
        </p>
        <p>@lang('app.terms.exploits.paragraph3')</p>
        <p>@lang('app.terms.exploits.paragraph4')</p>
    </x-text-section>

    <x-text-section :hdl="__('app.terms.multiBoxing.title')">
        <p>@lang('app.terms.multiBoxing.paragraph1')</p>
        <p>@lang('app.terms.multiBoxing.paragraph2')</p>
        <p>@lang('app.terms.multiBoxing.paragraph3')</p>
        <p>@lang('app.terms.multiBoxing.paragraph4')</p>
        <p>
            <a class="text-link" href="{{ route('password.request') }}"><x-icon name="link" size="2" />@lang('app.forgot.navTitle')</a><br />
            <a class="text-link" href="https://discuss.imperiumstellarum.io/index.php?/staff/"><x-icon name="link" size="2" />@lang('app.terms.exploits.staff')</a>
        </p>
    </x-text-section>

@endsection
