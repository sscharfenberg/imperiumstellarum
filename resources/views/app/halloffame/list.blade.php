@extends('layouts.app')

@section('content')

    <header class="page-head">
        <nav class="page-head__breadcrumbs">
            <a class="page-head__crumb" href="{{ route('welcome') }}" aria-label="@lang('app.home.navTitle')">
                <x-icon name="home" size="2" />
            </a>
            <a class="page-head__crumb" href="{{ route('halloffame') }}" aria-label="@lang('app.halloffame.title')">
                @lang('app.halloffame.title')
            </a>
        </nav>
        <h1 class="page-head__title">@lang('app.halloffame.title')</h1>
        <aside class="page-head__description">
            @lang('app.halloffame.description')
        </aside>
    </header>

@endsection
