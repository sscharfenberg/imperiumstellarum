<?php

namespace App\Http\Controllers;

use App\Models\FinishedGame;
use App\Models\FinishedGameParticipant;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HallOfFameController extends Controller
{

    /**
     * @function hall of fame index page
     * @param Request $request
     * @return View
     */
    public function index(Request $request):View
    {
        $locale = app()->getLocale();
        $format = 'd/m/Y h:iA e';
        if ($locale === 'de') $format = 'd.m.Y H:i e';
        $sortBy = $request->input(['sortBy']) ? $request->input(['sortBy']) : 'end_date';
        $order = $request->input(['order']) ? $request->input(['order']) : 'desc';
        $perPage = $request->input(['perPage']) ? $request->input(['perPage']) : '20';
        $games = FinishedGame::with('participants')
            ->with('winner')
            ->orderBy($sortBy, $order)
            ->paginate($perPage);
        return view('app.halloffame.list', compact(
            'games', 'sortBy', 'order', 'perPage', 'format'
        ));
    }

    /**
     * @function sort and filter hall of fame index page
     * @param Request $request
     * @return View
     */
    public function sortFilter(Request $request): View
    {
        $locale = app()->getLocale();
        $format = 'd/m/Y h:iA e';
        if ($locale === 'de') $format = 'd.m.Y H:i e';
        $formInput = $request->input(['sort']);
        $exploded = explode("--", $formInput);
        $sortBy = $exploded[0];
        $order = $exploded[1];
        $perPage = $request->input(['perPage']);
        $games = FinishedGame::with('participants')
            ->with('winner')
            ->orderBy($sortBy, $order)
            ->paginate($perPage);
        return view('app.halloffame.list', compact(
            'games', 'sortBy', 'order', 'perPage', 'format'
        ));
    }


    /**
     * @function show details of a finished game
     * @param Request $request
     * @return View
     */
    public function details (Request $request): View
    {
        $locale = app()->getLocale();
        $format = 'd/m/Y h:iA e';
        if ($locale === 'de') $format = 'd.m.Y H:i e';
        $gameId = $request->route('id');

        $sortBy = $request->input(['sortBy']) ? $request->input(['sortBy']) : 'population';
        $order = $request->input(['order']) ? $request->input(['order']) : 'desc';
        $perPage = $request->input(['perPage']) ? $request->input(['perPage']) : '20';

        $game = FinishedGame::find($gameId)
            ->with('participants')
            ->with('winner')
            ->first();
        $participants = FinishedGameParticipant::where('game_id', '=', $gameId)
            ->orderBy($sortBy, $order)
            ->paginate($perPage);

        return view('app.halloffame.details', compact(
            'game', 'participants', 'sortBy', 'order', 'perPage', 'format'
        ));
    }


    /**
     * @function sort and paginate finished game participants
     * @param Request $request
     * @return View
     */
    public function detailsSortFilter (Request $request): View
    {
        $locale = app()->getLocale();
        $format = 'd/m/Y h:iA e';
        if ($locale === 'de') $format = 'd.m.Y H:i e';
        $gameId = $request->route('id');

        $formInput = $request->input(['sort']);
        $exploded = explode("--", $formInput);
        $sortBy = $exploded[0];
        $order = $exploded[1];
        $perPage = $request->input(['perPage']);

        $game = FinishedGame::find($gameId)
            ->with('participants')
            ->with('winner')
            ->first();
        $participants = FinishedGameParticipant::where('game_id', '=', $gameId)
            ->orderBy($sortBy, $order)
            ->paginate($perPage);

        return view('app.halloffame.details', compact(
            'game', 'participants', 'sortBy', 'order', 'perPage', 'format'
        ));
    }

}
