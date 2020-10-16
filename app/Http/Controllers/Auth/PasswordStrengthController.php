<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use ZxcvbnPhp\Zxcvbn;
use App\Rules\PasswordStrength;

class PasswordStrengthController extends Controller
{

    /**
     * verify password strength
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function verify(Request $request) {
        $rules = new PasswordStrength;
        $zxcvbnInstance = new Zxcvbn();
        $password = $request->json('password');
        $returnData = [
            'score' => 0,
            'min' => $rules->minScore
        ];
        $check = $zxcvbnInstance->passwordStrength($password);
        $returnData['score'] = $check['score'];
        return response()->json($returnData);
    }

}
