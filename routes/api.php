<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordStrengthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

// verify password strength
Route::post('/passwordStrength', [PasswordStrengthController::class, 'verify']);

// protected auth routes
Route::middleware(['auth','verified'])->group(function () {
    Route::post('/test', [\App\Http\Controllers\LoginStatusController::class, 'test']);
});
