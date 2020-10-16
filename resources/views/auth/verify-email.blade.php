@extends('layouts.app')

@section('content')

    <header class="page-head">
        <nav class="page-head__breadcrumbs">
            <a class="page-head__crumb" href="{{ route('welcome') }}" aria-label="@lang('app.home.navTitle')">
                <x-icon name="home" size="2" />
            </a>
            <a class="page-head__crumb" href="/email/verify" aria-label="@lang('app.verify.navTitle')">
                @lang('app.verify.navTitle')
            </a>
        </nav>
        <h1 class="page-head__title">@lang('app.verify.title')</h1>
    </header>

    <form class="app-form" METHOD="POST" action="/email/verification-notification">
        @csrf
        <div class="form-row">
            @lang('app.verify.text')
        </div>
        <div class="form-row">
            <button class="app-btn app-btn--submit both">
                <x-icon name="mail" size="2" />
                <span>
                    @lang('app.verify.resend')
                    <x-loading size="24" />
                </span>
            </button>
        </div>
    </form>

@endsection
