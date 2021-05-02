@extends('layouts.app')

@section('content')

    <header class="page-head">
        <h1 class="page-head__title">@lang('app.home.title')</h1>
        <aside class="page-head__description">
            @lang('app.home.description')
        </aside>
    </header>

    <section class="hero">
        <span class="hero__text">@lang('app.home.hero.left')</span>
        <span class="hero__text">@lang('app.home.hero.middle')</span>
        <span class="hero__text">@lang('app.home.hero.right')</span>
    </section>

    <ul class="features">
        <li class="features__feature">
            <x-icon name="tech-laser" size="4" />
            <span>
                Inspired by games like
                <a class="text-link" href="https://en.wikipedia.org/wiki/The_Ashes_of_Empire" target="_blank">The Ashes of Empire</a>,
                <a class="text-link" href="http://www.planetarion.com/" target="_blank">Planetarion</a> or
                <a class="text-link" href="https://en.wikipedia.org/wiki/VGA_Planets">VGA Planets</a>,
                Imperium Stellarum mixes politics and strategy into a sort of 4x model: eXplore, eXpand, eXploit & eXterminate.
            </span>
        </li>
        <li class="features__feature">
            <x-icon name="github" size="4" />
            <span>
            Imperium Stellarum is open source. The sourcecode is availble on github with a permissible license. If you want, read the sourcecode, fork it and use it for your own game.
            </span>

        </li>
        <li class="features__feature">
            <x-icon name="tech-ftl" size="4" />
            <span>For each game, a new map is generated with potentially different parameters, leading to different experiences.</span>
        </li>
        <li class="features__feature">
            <x-icon name="shipyards" size="4" />
            <span>Customize your ship blueprints by deciding which modules to install. Offensive glass-cannons? Long-range ships with heavy shield protection? You decide.</span>
        </li>
        <li class="features__feature">Commodi eos, maxime perferendis quae quaerat quibusdam sapiente! Asperiores aspernatur cum iure maxime
            repudiandae. A maxime quasi quis quod voluptatem? Aliquid animi assumenda ducimus id. Blanditiis deserunt
            expedita similique tenetur?
        </li>
    </ul>



@endsection
