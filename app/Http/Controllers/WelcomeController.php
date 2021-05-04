<?php

namespace App\Http\Controllers;

use App\Models\FinishedGame;
use App\Models\Game;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class WelcomeController extends Controller
{


    /**
     * @function prepare collection of features.
     * @return Collection
     */
    private function gameFeatures (): Collection
    {
        $constant = collect([
            [
                'html' => '<p>'.__('app.home.features.4x.p1').'</p><p>'.__('app.home.features.4x.p2')
                    .'<a class="text-link" href="https://en.wikipedia.org/wiki/The_Ashes_of_Empire" target="_blank">The Ashes of Empire</a>,'
                    .'<a class="text-link" href="http://www.planetarion.com/" target="_blank">Planetarion</a>'
                    .__('app.home.features.4x.or')
                    .'<a class="text-link" href="https://en.wikipedia.org/wiki/VGA_Planets">VGA Planets</a></p>',
                'icon' => 'tech-railgun',
                'extra-class' => ''
            ],
            [
                'html' => '<p>'.__('app.home.features.free.p1').'</p><p>'.__('app.home.features.free.p2')
                    .'</p><p>'.__('app.home.features.free.p3').'</p>',
                'icon' => 'empire',
                'extra-class' => 'features__item--gorse'
            ]
        ]);

        $random = collect([
            [
                'html' => '<p>'.__('app.home.features.oss.p1').'</p><p>'.__('app.home.features.oss.p2').'</p>',
                'icon' => 'github',
                'extra-class' => ''
            ],
            [
                'html' => '<p>'.__('app.home.features.map.p1').'</p><p>'.__('app.home.features.map.p2').'</p>'
                    .'<p>'.__('app.home.features.map.p3').'</p>',
                'icon' => 'tech-ftl',
                'extra-class' => ''
            ],
            [
                'html' => '<p>'.__('app.home.features.blueprints.p1').'</p><p>'.__('app.home.features.blueprints.p2').'</p>',
                'icon' => 'shipyards',
                'extra-class' => 'features__item--viking'
            ],
            [
                'html' => '<p>'.__('app.home.features.privacy.p1').'</p><p>'.__('app.home.features.privacy.p2').'</p>',
                'icon' => 'encounters',
                'extra-class' => 'features__item--christine'
            ],
            [
                'html' => '<p>'.__('app.home.features.turn.p1').'</p><p>'.__('app.home.features.turn.p2')
                    .'</p><p>'.__('app.home.features.turn.p3').'</p>',
                'icon' => 'turn',
                'extra-class' => 'features__item--gorse'
            ],
            [
                'html' => '<p>'.__('app.home.features.diplomacy.p1').'</p><p>'.__('app.home.features.diplomacy.p2')
                    .'</p><p>'.__('app.home.features.diplomacy.p3').'</p>',
                'icon' => 'diplomacy',
                'extra-class' => 'features__item--christine'
            ]
        ])->shuffle()->take(3);

        return $random->concat($constant)->shuffle();
    }

    /**
     * @function welcome page
     * @param Request $request
     * @return View
     */
    public function index(Request $request):View
    {
        $locale = app()->getLocale();
        Carbon::setlocale($locale);
        $features = $this->gameFeatures();
        $activeGames = Game::where('active', '=', true)
            ->where('finished', '=', false)
            ->with('players')
            ->with('turns')
            ->orderBy('number', 'asc')
            ->get()
            ->take(3);
        $finishedGames = FinishedGame::with('participants')
            ->with('winner')
            ->orderBy('end_date', 'desc')
            ->get()
            ->take(3)
            ->map(function ($game) use ($locale) {
                $format = 'd/m/Y h:iA e';
                if ($locale === 'de') $format = 'd.m.Y H:i e';
                $game->end_date_formatted = $game->end_date->format($format);
                return $game;
            });
        $upcomingGames = Game::where('active', '=', false)
            ->where('finished', '=', false)
            ->where('start_date', '>', now())
            ->orderBy('start_date', 'asc')
            ->get()
            ->take(3)
            ->map(function ($game) use ($locale) {
                $format = 'd/m/Y h:iA e';
                if ($locale === 'de') $format = 'd.m.Y H:i e';
                $game->start_date_formatted = $game->start_date->format($format);
                return $game;
            });
        return view(
            'app.welcome',
            compact('features', 'activeGames', 'finishedGames', 'upcomingGames')
        );
    }

}
