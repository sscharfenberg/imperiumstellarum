<?php

namespace App\Http\Controllers\Game;

use App\Rules\Uppercase;
use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Player;
use App\Models\PlayerResource;
use App\Models\TechLevel;
use App\Services\PlayerDefaultService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;

class EnlistController extends Controller
{
    /**
     * @param Request $request
     * @param string $gameId
     * @return \Illuminate\Contracts\View\View|RedirectResponse
     */
    public function show(Request $request, string $gameId)
    {
        $game = Game::find($gameId);
        if (!$game) {
            return redirect()->back()
                ->with('status', __('admin.game.notfound'))
                ->with('severity', 'error');
        }
        return View::make('app.enlist', compact('game'));
    }

    /**
     * @function
     * @param Request $request
     * @param string $gameId
     * @return RedirectResponse
     */
    public function create(Request $request, string $gameId): RedirectResponse
    {
        $game = Game::find($gameId);
        if (!$game) {
            return redirect()->back()->with('status', __('admin.game.notfound'))->with('severity', 'error');
        }
        if (!$game->can_enlist) {
            return redirect()->route('dashboard')->with('status', __('app.enlist.notEnlistable'))->with('severity', 'error');
        }
        if ($game->active || $game->start_date < now()) {
            return redirect()->route('dashboard')->with('status', __('app.enlist.started'))->with('severity', 'error');
        }
        if (count($game->players) >= $game->max_players) {
            return redirect()->route('dashboard')->with('status', __('app.enlist.full'))->with('severity', 'error');
        }
        if ($game->players->contains('user_id', Auth::user()->id)) {
            return redirect()->route('dashboard')->with('status', __('app.enlist.alreadyEnlisted'))->with('severity', 'error');
        }

        // validate input
        $playerNames = $game->players->map(function($player) { return $player->name; });
        $tickers =  $game->players->map(function($player) { return $player->ticker; });
        $validator = Validator::make($request->input(), [
            'empire_name' => [
                'required',
                'string',
                'min:'.config('rules.player.name.min'),
                'max:'.config('rules.player.name.max'),
                Rule::notIn($playerNames)
            ],
            'ticker' => [
                'required',
                'string',
                'min:'.config('rules.player.ticker.min'),
                'max:'.config('rules.player.ticker.max'),
                Rule::notIn($tickers),
                new Uppercase
            ],
            'colour' => [
                'required',
                'string',
                'size:'.config('rules.player.colour'),
                'regex:/[a-fA-F0-9]\b/i'
            ]
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // create player
        $player = Player::create([
            'user_id' => Auth::user()->id,
            'game_id' => $game->id,
            'name' => $request->input(['empire_name']),
            'ticker' => $request->input(['ticker']),
            'colour' => $request->input(['colour'])
        ]);

        // add default settings for player - storage, techLevels, selected_player
        $d = new PlayerDefaultService;
        PlayerResource::insert($d->resources($player->id, $game->id));
        TechLevel::insert($d->techLevels($player->id));
        $user = Auth::user();
        $user->selected_player = $player->id;
        $user->save();

        // all done!
        return redirect()->route('dashboard')
            ->with('status', __('app.enlist.success', ['game' => $game->number]))
            ->with('severity', 'success');
    }


    /**
     * @function quit game, delete player
     * @param Request $request
     * @param $playerId
     * @return RedirectResponse
     * @throws \Exception
     */
    public function delete (Request $request, $playerId): RedirectResponse
    {
        $player = Player::find($playerId);
        if (!$player || $player->game->finished) {
            return redirect()->back()
                ->with('status', __('app.quit.notFound'))
                ->with('severity', 'error');
        }
        $player->delete();
        $user = Auth::user();
        if ($user->selected_player === $player->id) {
            $user->selected_player = null;
            $user->save();
        }
        return redirect()->back()
            ->with('status', __('app.quit.success', ['game' => $player->game->number]))
            ->with('severity', 'success');
    }
}
