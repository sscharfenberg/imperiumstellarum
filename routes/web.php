<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\SuspensionController;
use App\Http\Controllers\Admin\GamesController;
use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Admin\PlayerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Public routes, no auth
 */
// Homepage
Route::get('/', function () {
    return view('app.welcome');
})->name('welcome');
// Language Switcher
Route::get('/lang/{locale}', [\App\Http\Controllers\LocaleController::class, 'change'])
    ->name('locale');
// Privacy Policy
Route::get('/privacy', function() {
    return view('app.privacy');
})->name('privacy');
// Imprint / Legal Notice
Route::get('/legal-notice', function() {
    return view('app.imprint');
})->name('imprint');
// Terms of use
Route::get('/terms-of-use', function() {
    return view('app.terms');
})->name('terms');

/**
 * Fortify Overrides
 */
// Send a new email verification notification
Route::post('/email/verification-notification',
    [App\Http\Controllers\Auth\EmailVerificationNotificationController::class, 'store']);
// verify email endpoint
Route::get('/email/verify/{id}/{hash}',
    [App\Http\Controllers\Auth\VerifyEmailController::class, '__invoke'])
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

/**
 * Internal (authed) routes
 */
// authed routes
Route::middleware(['auth','verified', 'suspended'])->group(function () {
    // show dashboard
    Route::get('/dashboard', [DashboardController::class, 'show'])
        ->name('dashboard');
    // change email from dashboard
    Route::post('/dashboard/email', [DashboardController::class, 'email'])
        ->name('dashboard-email');
    // change password from dashboard
    Route::post('/dashboard/password', [DashboardController::class, 'password'])
        ->name('dashboard-password');
    // mail opt-in from dashboard
    Route::post('/dashboard/mail-optin', [DashboardController::class, 'notification'])
        ->name('dashboard-optin');
    // delete account from dashboard
    Route::post('/dashboard/delete', [DashboardController::class, 'delete'])
        ->name('dashboard-delete');
    // show enlist game
    Route::get('/game/{game}/enlist', [\App\Http\Controllers\Game\EnlistController::class, 'show'])
        ->name('show-enlist');
    // enlist game
    Route::post('/game/{game}/enlist', [\App\Http\Controllers\Game\EnlistController::class, 'create'])
        ->name('enlist');
    // quit game
    Route::post('/game/player/{player}/delete', [\App\Http\Controllers\Game\EnlistController::class, 'delete'])
        ->name('quit');
    // select game
    Route::get('/game/{game}/select', [\App\Http\Controllers\Game\SelectGameController::class, 'select'])
        ->name('select-game');
});

/**
 * Game route
 */
Route::middleware(['auth','verified', 'suspended', 'gameStarted', 'enlisted'])->group(function () {
    // game routes
    Route::get('/game/{game}/{any}', [\App\Http\Controllers\Game\GameController::class, 'show'])
        ->where('any', '.*')
        ->name('show-game');
});

/**
 * Admin routes
 */
Route::middleware(['auth','verified','suspended','can:useMod'])->group(function () {
    // Admin home screen
    Route::get('/admin', [HomeController::class, 'show'])
        ->name('admin');

    // users overview screen
    Route::get('/admin/users', [UsersController::class, 'show'])
        ->name('users');
    // sort + filter users
    Route::post('/admin/users', [UsersController::class, 'sortFilter']);
    // view user
    Route::get('/admin/user/{user}', [UserController::class, 'details'])
        ->name('user-details');
    // change user details
    Route::post('/admin/user/{user}', [UserController::class, 'change'])
        ->name('user-change');
    // suspend user
    Route::post('/admin/user/{user}/suspend', [SuspensionController::class, 'suspend'])
        ->name('suspend-user');
    // lift suspension
    Route::post('/admin/suspension/{suspension}/lift', [SuspensionController::class, 'delete'])
        ->name('delete-suspension')->middleware(['can:useAdmin']);

    // games overview screen
    Route::get('/admin/games', [GamesController::class, 'show'])
        ->name('games');
    // sort games
    Route::post('/admin/games', [GamesController::class, 'sortFilter']);

    // show view: create or edit game
    Route::get('/admin/game/create', [GameController::class, 'showCreate'])
        ->name('show-create-game')->middleware('can:useAdmin');
    // create new game
    Route::post('/admin/game/create', [GameController::class, 'create'])
        ->name('create-game')->middleware('can:useAdmin');
    // view game
    Route::get('/admin/game/{game}', [GameController::class, 'details'])
        ->name('game-details')->middleware('can:useAdmin');
    // edit game
    Route::post('/admin/game/{game}', [GameController::class, 'update'])
        ->name('game-edit')->middleware('can:useAdmin');
    // view players
    Route::get('/admin/game/{game}/players', [PlayerController::class, 'show'])
        ->name('game-players')->middleware('can:useAdmin');
    Route::post('/admin/game/{game}/players', [PlayerController::class, 'sortFilter'])
        ->middleware('can:useAdmin');

    // view map preview
    Route::get('/admin/game/{game}/map', [GameController::class, 'showPreview'])
        ->name('preview-map')->middleware('can:useAdmin');
    // generate map preview
    Route::post('/admin/game/{game}/map', [GameController::class, 'generatePreview'])
        ->middleware('can:useAdmin');
    // seed map
    Route::post('/admin/game/{game}/seed', [GameController::class, 'seedGame'])
        ->name('seed-game')->middleware('can:useAdmin');

});
