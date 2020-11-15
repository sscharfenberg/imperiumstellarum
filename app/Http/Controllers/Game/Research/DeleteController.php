<?php

namespace App\Http\Controllers\Game\Research;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Services\FormatApiResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeleteController extends Controller
{

    public function handle(Request $request)
    {
        $player = Player::find(Auth::user()->selected_player);
        $jobId = $request->input("id");

        dd($jobId);

        // send update research jobs to client
        $updatedPlayer = Player::find(Auth::user()->selected_player);
        $f = new FormatApiResponseService;
        return response()->json([
            'researchJobs' => $updatedPlayer->researches->map(function ($research) use ($f) {
                return $f->formatResearchJob($research);
            })
        ]);
    }

}
