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


Route::prefix('player')->middleware('auth:sanctum')->group(function() {
  Route::get('/list', [Player::class, 'index']);
  Route::get('/{player}/teams', [Player::class, 'getTeams']);
  Route::get('/{player}/details', [Player::class, 'getDetails']);
  Route::post('/guest', [Player::class, 'add']);
  Route::put('/{player}', [Player::class, 'update']);
  Route::put('/{player}/team/{team}', [Player::class, 'assignToTeam']);
  Route::delete('/{player}/team/{team}', [Player::class, 'unassignFromTeam']);
  Route::delete('/{player}', [Player::class, 'delete']);

  Route::get('/positions', [Player::class, 'getPlayerPostions'])
    ->withoutMiddleware('auth:sanctum');
});

Route::prefix('team')->middleware('auth:sanctum')->group(function() {
  Route::get('/list', [Team::class, 'index']);
  Route::get('/{team}/players', [Team::class, 'getPlayers']);
  Route::get('/{team}/details', [Team::class, 'details']);
  Route::post('/', [Team::class, 'add']);
  Route::put('/{team}', [Team::class, 'update']);
  Route::delete('/{team}', [Team::class, 'delete']);
  Route::delete('/{team}', [Team::class, 'delete']);
});

Route::prefix('match')->middleware('auth:sanctum')->group(function() {
  Route::get('/list', [MatchController::class, 'index']);
  Route::get('/{matchId}/details', function(Request $req, $matchId) {
    return (new MatchController())->index($req, null, $matchId);
  });
  Route::post('/', [MatchController::class, 'add']);
  Route::put('/{match}', [MatchController::class, 'update']);
  Route::delete('/{match}', [MatchController::class, 'delete']);
});

Route::prefix('lineup')->middleware('auth:sanctum')->group(function() {
  Route::get('/list/match/{match}', [Lineup::class, 'index']);
  Route::post('/', [Lineup::class, 'add']);
  Route::delete('/match/{match}/team/{team?}', [Lineup::class, 'delete']);
});


Route::prefix('event')->middleware('auth:sanctum')->group(function() {
  Route::get('/list', [Event::class, 'index']);
  Route::get('/{event}/request/list', [EventRequest::class, 'index']);
  Route::post('/', [Event::class, 'add']);
  Route::post('/request', [EventRequest::class, 'add']);
  Route::post('/request/{eventReq}/accept', [EventRequest::class, 'acceptReq']);
  Route::delete('/{event}', [Event::class, 'delete']);
  Route::delete('/request/{eventReq}', [EventRequest::class, 'delete']);
});

Route::prefix('home')->middleware('auth:sanctum')->group(function() {
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
    $teams = (new Team())->index($req, true);

    return ["matches" => $matches, "events" => $events, "teams"=> $teams];
  });
});


Route::prefix('stadium')->group(function() {
  Route::get('/list', [Stadium::class, 'index']);
  Route::get('/{stadium}/details', [Stadium::class, 'details']);
  Route::post('/', [Stadium::class, 'add'])->middleware('auth:sanctum');
  Route::delete('/{stadium}', [Stadium::class, 'delete']);
});
