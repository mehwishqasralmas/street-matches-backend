<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User;
use App\Http\Controllers\Player;
use App\Http\Controllers\Team;
use App\Http\Controllers\Stadium;
use App\Http\Controllers\Mtch as MatchController;
use App\Http\Controllers\Lineup;
use App\Http\Controllers\Event;
use App\Http\Controllers\EventRequest;
use App\Http\Controllers\Resources\Image;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function() {

    Route::post('/login', [User::class, 'logIn']);
    Route::post('/signup', [User::class, 'signUp']);
    Route::post('/forget-password', [User::class, 'forgetPassword']);
    Route::post('/reset-password', [User::class, 'resetPassword']);

    Route::get('/user', [User::class, 'user'])->middleware('auth:sanctum');
});

Route::prefix('resource')->group(function(){
  Route::post('/img/upload', [Image::class, 'upload']);
});


Route::prefix('player')->group(function() {
  Route::get('/list', [Player::class, 'index']);
  Route::get('/{player}/teams', [Player::class, 'getTeams']);
  Route::get('/{player}/details', [Player::class, 'getDetails']);

  Route::post('/guest', [Player::class, 'add'])->middleware('auth:sanctum');
  Route::put('/{player}', [Player::class, 'update'])->middleware('auth:sanctum');
  Route::put('/{player}/team/{team}', [Player::class, 'assignToTeam'])->middleware('auth:sanctum');
  Route::delete('/{player}/team/{team}', [Player::class, 'unassignFromTeam'])->middleware('auth:sanctum');
  Route::delete('/{player}', [Player::class, 'delete'])->middleware('auth:sanctum');

  Route::get('/positions', [Player::class, 'getPlayerPostions']);
});

Route::prefix('team')->group(function() {
  Route::get('/list', [Team::class, 'index']);
  Route::get('/{team}/players', [Team::class, 'getPlayers']);
  Route::get('/{team}/details', [Team::class, 'details']);
  Route::post('/', [Team::class, 'add'])->middleware('auth:sanctum');
  Route::put('/{team}', [Team::class, 'update'])->middleware('auth:sanctum');
  Route::delete('/{team}', [Team::class, 'delete'])->middleware('auth:sanctum');
  Route::delete('/{team}', [Team::class, 'delete'])->middleware('auth:sanctum');
});

Route::prefix('match')->middleware('auth:sanctum')->group(function() {
  Route::get('/list', [MatchController::class, 'index'])->withoutMiddleware('auth:sanctum');
  Route::get('/{matchId}/details', function(Request $req, $matchId) {
    return (new MatchController())->index($req, null, $matchId);
  });
  Route::post('/', [MatchController::class, 'add']);
  Route::put('/{match}', [MatchController::class, 'update']);
  Route::delete('/{match}', [MatchController::class, 'delete']);
});

Route::prefix('lineup')->middleware('auth:sanctum')->group(function() {
  Route::get('/list/match/{match}', [Lineup::class, 'index'])->withoutMiddleware('auth:sanctum');
  Route::post('/', [Lineup::class, 'add']);
  Route::delete('/match/{match}/team/{team?}', [Lineup::class, 'delete']);
});


Route::prefix('event')->middleware('auth:sanctum')->group(function() {
  Route::get('/list', [Event::class, 'index'])->withoutMiddleware('auth:sanctum');
  Route::get('/{event}/request/list', [EventRequest::class, 'index'])->withoutMiddleware('auth:sanctum');
  Route::post('/', [Event::class, 'add']);
  Route::post('/request', [EventRequest::class, 'add']);
  Route::post('/request/{eventReq}/accept', [EventRequest::class, 'acceptReq']);
  Route::delete('/{event}', [Event::class, 'delete']);
  Route::delete('/request/{eventReq}', [EventRequest::class, 'delete']);
});

Route::prefix('home')->group(function() {
  Route::get('/data', function (Request $req) {
    $events = (new Event())->index($req);
    $matches = (new MatchController())->index($req, ">=,0");
    $teams = (new Team())->index($req, true);
    $stadiums = (new Stadium())->index($req, false);

    return ["matches" => $matches, "events" => $events, "teams"=> $teams, "stadiums" => $stadiums];
  });
});

Route::prefix('user')->middleware('auth:sanctum')->group(function() {
  Route::put('/info', [User::class, 'updateInfo']);
  Route::delete('/account', [User::class, 'delete']);

  Route::get('/activities', function (Request $req) {
    $events = (new Event())->index($req);
    $matches = (new MatchController())->index($req, ">=,0");
    $teams = (new Team())->index($req);

    return ["matches" => $matches, "events" => $events, "teams"=> $teams];
  });
});


Route::prefix('stadium')->group(function() {
  Route::get('/list', [Stadium::class, 'index']);
  Route::get('/{stadium}/details', [Stadium::class, 'details']);
  Route::post('/', [Stadium::class, 'add'])->middleware('auth:sanctum');
  Route::delete('/{stadium}', [Stadium::class, 'delete']);
});
