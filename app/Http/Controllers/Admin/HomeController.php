<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MessageReport;
use App\Models\Suspension;
use App\Models\User;
use App\Models\Game;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    /**
     * Show admin welcome screen
     *
     * @return View
     */
    public function show(): View
    {
        $userCount = User::count();
        $unverifiedUserCount = User::where('email_verified_at', null)->count();
        $activeGames = Game::where('active', true)->count();
        $enlistableGames = Game::where('can_enlist', true)->count();
        $gamesToStart = Game::where('active', false)->where('start_date', '>', now())->count();
        $finishedGames = Game::where('active', false)->where('start_date', '<', now())->count();
        $reportsTotal = MessageReport::get();
        $reportsResolved = $reportsTotal->whereNotNull('resolved_admin')->count();
        $reportsUnresolved = $reportsTotal->whereNull('resolved_admin')->count();

        $suspensions = [];
        foreach (Suspension::all() as $suspension) {
            if ($suspension->isActive() && !in_array($suspension->user_id, $suspensions)) {
                $suspensions[] = $suspension->user_id;
            }
        }

        return view('admin.home', [
            'userCount' => $userCount,
            'unverifiedCount' => $unverifiedUserCount,
            'suspendedUsers' => count($suspensions),
            'activeGames' => $activeGames,
            'enlistableGames' => $enlistableGames,
            'gamesToStart' => $gamesToStart,
            'finishedGames' => $finishedGames,
            'reportsTotal' => count($reportsTotal),
            'reportsResolved' => $reportsResolved,
            'reportsUnresolved' => $reportsUnresolved
        ]);
    }

}
