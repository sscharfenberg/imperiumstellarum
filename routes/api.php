<?php

use Illuminate\Support\Facades\Route;

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
Route::post('/passwordStrength', [App\Http\Controllers\Auth\PasswordStrengthController::class, 'verify']);
// update drawer status for user
Route::post('/drawer', [App\Http\Controllers\DrawerController::class, 'update'])
    ->middleware(['auth']);

// protected auth routes
Route::middleware(['auth','verified'])->group(function () {
    Route::post('/test', [\App\Http\Controllers\LoginStatusController::class, 'test']);
});
