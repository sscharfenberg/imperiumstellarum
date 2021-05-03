<?php

namespace App\Http\Controllers;

use App\Models\Game;
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
        return collect([
            [
                'html' => '<p>'.__('app.home.features.4x.p1').'</p><p>'.__('app.home.features.4x.p2')
                    .'<a class="text-link" href="https://en.wikipedia.org/wiki/The_Ashes_of_Empire" target="_blank">The Ashes of Empire</a>,'
                    .'<a class="text-link" href="http://www.planetarion.com/" target="_blank">Planetarion</a>'
                    .__('app.home.features.4x.or')
                    .'<a class="text-link" href="https://en.wikipedia.org/wiki/VGA_Planets">VGA Planets</a></p>',
                'icon' => 'tech-laser',
                'extra-class' => ''
            ],
            [
                'html' => '<p>'.__('app.home.features.oss.p1').'</p><p>'.__('app.home.features.oss.p2').'</p>',
                'icon' => 'github',
                'extra-class' => ''
            ],
            [
                'html' => '<p>'.__('app.home.features.free.p1').'</p><p>'.__('app.home.features.free.p2')
                    .'</p><p>'.__('app.home.features.free.p3').'</p>',
                'icon' => 'empire',
                'extra-class' => 'features__item--gorse'
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
        ]);
    }


    /**
     * @function welcome page
     * @param Request $request
     * @return View
     */
    public function index(Request $request):View
    {

        $features = $this->gameFeatures();
        $activeGames = Game::where('active', '=', true)
            ->where('finished', '=', false)
            ->with('players')
            ->with('turns')
            ->get();
        $finishedGames = Game::where('active', '=', false)
            ->where('finished', '=', true)
            ->with('participants')
            ->get();

        return view('app.welcome', compact('features', 'activeGames', 'finishedGames'));
    }

}
