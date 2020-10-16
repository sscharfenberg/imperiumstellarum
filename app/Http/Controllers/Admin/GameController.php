<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;

use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use App\Services\SeedGameService;

class GameController extends Controller
{

    /**
     * Show view: create game
     *
     * @return View
     */
    public function showCreate()
    {
        return view('admin.game.create');
    }

    /**
     * @function Create a new game
     * @param Request $request
     * @return RedirectResponse
     */
    public function create(Request $request)
    {
        $tzOffset = intval($request->input(['start_date_timezone_offset']));
        $validator = Validator::make($request->input(), [
            'dimensions' => ['required', 'numeric', 'min:10', 'max:200'],
            'start_date' => ['required', 'date', 'date_format:d.m.Y H:i', 'after_or_equal:'.now()->subMinute($tzOffset + 1)],
            'turn_duration' => ['required', 'numeric', 'min:1', 'max:120']
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $start_date = Carbon::parse($request->input(['start_date']))->addMinute($tzOffset);
            $gameNumber = 1;
            // get highest game number
            $games = DB::table('games')->orderBy('number', 'desc')->limit(1)->get();
            if ($games->count() !== 0) {
                $gameNumber = intval($games->first()->number) + 1; // and add 1.
            }
            $game = Game::create([
                'number' => $gameNumber,
                'dimensions' => $request->input(['dimensions']),
                'start_date' => $start_date,
                'turn_due' => Carbon::parse($start_date)->addMinute($request->input(['turn_duration'])),
                'turn_duration' => $request->input(['turn_duration'])
            ]);
            return redirect()->route('game-edit', ['game' => $game->id])
                ->with('status', __('admin.game.create.success'))
                ->with('severity','success');
        }
    }

    /**
     * @function game details
     * @param Request $request
     * @param string $id
     * @return View|RedirectResponse
     */
    public function details(Request $request, string $id)
    {
        $game = Game::find($id);
        if(!$game) {
            return redirect()->back()
                ->with('status', __('admin.game.notfound'))
                ->with('severity', 'error');
        } else {
            return view('admin.game.details', compact('game'));
        }
    }

    /**
     * @function update game
     * @param Request $request
     * @param string $id
     * @return View|RedirectResponse
     */
    public function update(Request $request, string $id)
    {
        $game = Game::find($id);
        $input = $request->input();
        if(!$game) {
            return redirect()->back()
                ->with('status', __('admin.game.edit.notfound'))
                ->with('severity', 'error');
        }
        $validator = Validator::make($request->input(), [
            'dimensions' => ['required', 'numeric', 'min:10', 'max:200'],
            'start_date' => ['required', 'date', 'date_format:d.m.Y H:i'],
            'turn_duration' => ['required', 'numeric', 'min:1', 'max:120']
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($input['dimensions']) $game->dimensions = $input['dimensions'];
        if ($input['start_date']) $game->start_date = $input['start_date'];
        if ($input['turn_duration']) $game->turn_duration = $input['turn_duration'];
        $game->can_enlist = $input['can_enlist'] === "on";
        $game->active = $input['active'] === "on";
        $game->processing = $input['processing'] === "on";
        $game->save();
        return redirect()->back()
            ->with('status', __('admin.game.edit.success'))
            ->with('severity', 'success');
    }

    /**
     * @function show map preview
     * @param Request $request
     * @param string $id
     * @return View|RedirectResponse
     */
    public function showPreview(Request $request, string $id)
    {
        $game = Game::find($id);
        if(!$game) {
            return redirect()->back()
                ->with('status', __('admin.game.notfound'))
                ->with('severity', 'error');
        }
        if ($game->map) {
            $points = json_decode($game->map);
            $sectors = array_count_values(array_column($points, 'type'));
            $map = [];
            // prepare a multidimensional empty map array with "0" as values
            for ($i = 0; $i < $game->dimensions; $i++) {
                $map[] = array_fill(0, $game->dimensions, 0);
            }
            // fill each point with "1" or "2" for stars
            foreach($points as $point) {
                $map[$point->y][$point->x] = $point->type;
            }
            return view('admin.game.map', compact('game', 'map', 'sectors'));
        }

        return view('admin.game.map', compact('game'));
    }


    /**
     * @function generate map preview
     * @param Request $request
     * @param string $id
     * @return RedirectResponse
     */
    public function generatePreview(Request $request, string $id) {
        $game = Game::find($id);
        if(!$game) {
            return redirect()->back()
                ->with('status', __('admin.game.notfound'))
                ->with('severity', 'error');
        } else {
            $game->dimensions = $request->input(['dimensions']);
            $game->map = $request->input(['map']);
            $game->save();
            return redirect()->back()
                ->withInput();
        }
    }


    /**
     * @function seed game with stars and planets
     * @param Request $request
     * @param string $id
     * @throws \Exception
     * @return RedirectResponse
     */
    public function seedGame(Request $request, string $id) {
        $game = Game::find($id);
        if(!$game || !$game->map) {
            return redirect()->back()
                ->with('status', __('admin.game.notfound'))
                ->with('severity', 'error');
        }
        $s = new SeedGameService;
        $s->seedStars($game);
        $s->seedPlanets($game);
        $playerStars =  array_count_values(array_column(json_decode($game->map), 'type'))['2'];
        $game->max_players = $playerStars;
        $game->map = null;
        $game->save();
        return redirect()->route('game-details', ['game' => $game->id])
            ->with('status', __('admin.game.seed.success'))
            ->with('severity', 'success');
    }


}
