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
// calculate new population
Route::post('/game/populationChange',
    [\App\Http\Controllers\Game\Empire\PopulationController::class, 'projectNewPopulation']);


/**
 * authenticated api routes
 */

Route::middleware(['auth', 'suspended'])->group(function () {
    // update drawer status for user
    Route::post('/drawer', [App\Http\Controllers\DrawerController::class, 'update']);
});

/**
 * game api routes without processing.
 */

Route::middleware(['auth', 'verified', 'suspended', 'gameStarted', 'enlisted'])->group(function () {

    /**
     * empire api calls
     */

    // empire game data
    Route::get('/game/{game}/empire',
        [\App\Http\Controllers\Game\Empire\EmpireController::class, 'gameData']);

    // change star name
    Route::post('/game/{game}/empire/star_name',
        [\App\Http\Controllers\Game\Empire\StarNameController::class, 'handle']);

    // change planet food consumption
    Route::post('/game/{game}/empire/food_consumption',
        [\App\Http\Controllers\Game\Empire\PopulationController::class, 'changeFoodConsumption']);

});

/**
 * game api routes with processing check.
 */
Route::middleware(['auth', 'verified', 'suspended', 'gameStarted', 'enlisted', 'notProcessing'])->group(function () {

    // install storage upgrade
    Route::post('/game/{game}/storage_upgrade',
        [\App\Http\Controllers\Game\StorageUpgradeController::class, 'install']);

});
