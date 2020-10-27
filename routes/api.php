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

/**
 * public api routes.
 */

// verify password strength
Route::post('/passwordStrength', [App\Http\Controllers\Auth\PasswordStrengthController::class, 'verify']);


/**
 * authenticated api routes
 */

Route::middleware(['auth', 'suspended'])->group(function () {
    // update drawer status for user
    Route::post('/drawer', [App\Http\Controllers\DrawerController::class, 'update']);
});

/**
 * game api routes.
 */

Route::middleware(['auth', 'verified', 'suspended', 'gameStarted', 'enlisted'])->group(function () {

    /**
     * common api calls, not area specific
     */

    // install storage upgrade
    Route::post('/game/{game}/storage_upgrade',
        [\App\Http\Controllers\Game\StorageUpgradeController::class, 'install']);

    /**
     * empire api calls
     */

    // empire game data
    Route::get('/game/{game}/empire', [\App\Http\Controllers\Game\EmpireController::class, 'gameData']);

});
