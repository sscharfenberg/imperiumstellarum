<?php

namespace App\Http\Controllers\Game\Messages;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessagesController extends Controller
{

    /**
     * @function api gameData for "diplomacy" endpoint
     * @param Request $request
     * @return JsonResponse
     */
    public function gameData(Request $request):JsonResponse
    {
        $a = new ApiService;
        $defaultApiData = $a->defaultData($request);

        $returnData = [
            'inbox' => [],
            'outbox' => [],
        ];

        return response()->json(array_merge($defaultApiData, $returnData));
    }

}
