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

    tbd: terms of use

@endsection
