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
// get a random shipClass name
Route::get('/game/shipyards/{class}/randomName',
    [\App\Http\Controllers\Game\Shipyards\ShipyardsController::class, 'randomClassName']);

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

Route::middleware([
    'auth',
    'verified',
    'suspended',
    'gameStarted',
    'enlisted'
])->group(function () {

    /**
     * empire api calls
     */

    // empire game data
    Route::get('/game/{game}/empire',
        [\App\Http\Controllers\Game\Empire\EmpireController::class, 'gameData']);

    // change star name
    Route::post('/game/{game}/empire/star_name',
        [\App\Http\Controllers\Game\Empire\StarNameController::class, 'handle']);

    /**
     * research api calls
     */

    // empire game data
    Route::get('/game/{game}/research',
        [\App\Http\Controllers\Game\Research\ResearchController::class, 'gameData']);

    /**
     * starchart api calls
     */
    Route::get('/game/{game}/starchart',
        [\App\Http\Controllers\Game\Starchart\StarchartController::class, 'gameData']);

    /**
     * shipyards api calls
     */
    Route::get('/game/{game}/shipyards',
        [\App\Http\Controllers\Game\Shipyards\ShipyardsController::class, 'gameData']);

    /**
     * fleets api calls
     */
    Route::get('/game/{game}/fleets',
        [\App\Http\Controllers\Game\Fleets\FleetsController::class, 'gameData']);

    /**
     * diplomacy api calls
     */
    Route::get('/game/{game}/diplomacy',
        [\App\Http\Controllers\Game\Diplomacy\DiplomacyController::class, 'gameData']);

});

/**
 * game api routes with processing check.
 */
Route::middleware([
    'auth',
    'verified',
    'suspended',
    'gameStarted',
    'enlisted',
    'notProcessing'
])->group(function () {

    // install storage upgrade
    Route::post('/game/{game}/storage_upgrade',
        [\App\Http\Controllers\Game\StorageUpgradeController::class, 'install']);

    /**
     * empire api calls
     */

    // change planet food consumption
    Route::post('/game/{game}/empire/food_consumption',
        [\App\Http\Controllers\Game\Empire\PopulationController::class, 'changeFoodConsumption']);
    // install harvester
    Route::post('/game/{game}/empire/harvester',
        [\App\Http\Controllers\Game\Empire\HarvesterController::class, 'install']);
    // build shipyard
    Route::post('/game/{game}/empire/shipyard',
        [\App\Http\Controllers\Game\Empire\ShipyardController::class, 'install']);
    // upgrade shipyard
    Route::post('/game/{game}/empire/shipyard/upgrade',
        [\App\Http\Controllers\Game\Empire\ShipyardController::class, 'upgrade']);

    /**
     * research api calls
     */

    // set research priority
    Route::post('/game/{game}/setResearchPriority',
        [\App\Http\Controllers\Game\Research\ResearchPriorityController::class, 'change']);
    // enqueue research job
    Route::post('/game/{game}/research/enqueue',
        [\App\Http\Controllers\Game\Research\EnqueueController::class, 'handle']);
    // change queue sort order
    Route::post('/game/{game}/research/order',
        [\App\Http\Controllers\Game\Research\QueueOrderController::class, 'handle']);
    // delete research job
    Route::post('/game/{game}/research/delete',
        [\App\Http\Controllers\Game\Research\DeleteController::class, 'handle']);

    /**
     * starchart api calls
     */
    // get star details
    Route::get('/game/{game}/star/{star}/details',
        [\App\Http\Controllers\Game\Starchart\StarDetailsController::class, 'handle']);
    // get fleet travelTimes to a certain star
    Route::post('/game/{game}/starchart/travelTime',
        [\App\Http\Controllers\Game\Starchart\StarDistanceController::class, 'handle']);


    /**
     * shipyards api calls
     */

    // create blueprint
    Route::post('/game/{game}/shipyards/blueprint',
        [\App\Http\Controllers\Game\Shipyards\CreateBlueprintController::class, 'handle']);
    // delete blueprint
    Route::post('/game/{game}/shipyards/blueprint/delete',
        [\App\Http\Controllers\Game\Shipyards\DeleteBlueprintController::class, 'handle']);
    // rename blueprint
    Route::post('/game/{game}/shipyards/blueprint/rename',
        [\App\Http\Controllers\Game\Shipyards\RenameBlueprintController::class, 'handle']);
    // create construction contract
    Route::post('/game/{game}/shipyards/contract/construct',
        [\App\Http\Controllers\Game\Shipyards\CreateConstructionContractController::class, 'handle']);
    // delete construction contract
    Route::post('/game/{game}/shipyards/contract/delete',
        [\App\Http\Controllers\Game\Shipyards\DeleteConstructionContractController::class, 'handle']);

    /**
     * fleets api calls
     */
    // create fleet
    Route::post('/game/{game}/fleets/create',
        [\App\Http\Controllers\Game\Fleets\CreateFleetController::class, 'handle']);
    // change fleet name
    Route::post('/game/{game}/fleets/name',
        [\App\Http\Controllers\Game\Fleets\ChangeFleetNameController::class, 'handle']);
    // delete fleet
    Route::post('/game/{game}/fleets/delete',
        [\App\Http\Controllers\Game\Fleets\DeleteFleetController::class, 'handle']);
    // change ship name
    Route::post('/game/{game}/fleets/shipName',
        [\App\Http\Controllers\Game\Fleets\ChangeShipNameController::class, 'handle']);
    // transfer ships
    Route::post('/game/{game}/fleets/transfer',
        [\App\Http\Controllers\Game\Fleets\FleetTransferController::class, 'handle']);

    // find a star owned by the player and get information
    Route::post('/game/{game}/fleets/destination/ownSystems',
        [\App\Http\Controllers\Game\Fleets\FindDestinationController::class, 'playerSystems']);
    // find a star by coords and get information
    Route::post('/game/{game}/fleets/destination/byCoords',
        [\App\Http\Controllers\Game\Fleets\FindDestinationController::class, 'byCoords']);
    // find the stars of an empire by ticker
    Route::post('/game/{game}/fleets/destination/systemsByTicker',
        [\App\Http\Controllers\Game\Fleets\FindDestinationController::class, 'systemsByTicker']);
    // send fleet to destination
    Route::post('/game/{game}/fleets/destination/send',
        [\App\Http\Controllers\Game\Fleets\MoveFleetController::class, 'handle']);

});
