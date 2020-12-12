<?php

namespace App\Http\Controllers\Game\Empire;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Star;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Services\FormatApiResponseService;

class StarNameController extends Controller
{


    /**
     * @function validate input
     * @param Request $request
     * @throws ValidationException
     */
    private function validateInput (Request $request)
    {
        $messages = [
            'required' => __('game.empire.errors.star.required'),
            'between' => __('game.empire.errors.star.between')
        ];
        $rules = [
            'name' => 'required|between:'.config('rules.stars.name.min').','.config('rules.stars.name.max')
        ];
        Validator::make($request->input(), $rules, $messages)->validate();
    }


    /**
     * @function verify player owns the star
     * @param Player $player
     * @param string $starId
     * @return bool
     */
    private function playerOwnsStar (Player $player, string $starId): bool
    {
        $playerStar = $player->stars->find($starId);
        if ($playerStar) return true;
        return false;
    }


    /**
     * @function handle storage upgrade installation
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function handle (Request $request): JsonResponse
    {
        $player = Player::find(Auth::user()->selected_player);
        $input = $request->input();

        if (!$this->playerOwnsStar($player, $input['id'])) {
            return response()
                ->json(['error' => __('game.empire.errors.star.owner')], 419);
        }
        $this->validateInput($request);

        // all good, change name of star
        $star = Star::find($input['id']);
        $star->name = $input['name'];
        $star->save();

        $f = new FormatApiResponseService;
        return response()->json([
            'star' => $f->formatStar($star)
        ], 200);
    }

}
