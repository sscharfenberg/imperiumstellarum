<?php

namespace App\Providers;

use App\View\Components\CheckboxComponent;
use App\View\Components\IconComponent;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(200);

        // Blade directives
        Blade::directive('sprite', function () {
            return "<?php if (Storage::disk('local')->exists('iconsprite.svg')) { echo Storage::disk('local')->get('iconsprite.svg'); } ?>";
        });

        // Blade components
        Blade::component('icon', IconComponent::class);
        Blade::component('checkbox', CheckboxComponent::class);

    }
}
