<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User;
use App\Http\Controllers\Player;
use App\Http\Controllers\Team;
use App\Http\Controllers\Match;
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
  Route::post('/', [Team::class, 'add']);
  Route::put('/{team}', [Team::class, 'update']);
  Route::delete('/{team}', [Team::class, 'delete']);
});

Route::prefix('match')->middleware('auth:sanctum')->group(function() {
  Route::get('/list', [Match::class, 'index']);
  Route::post('/', [Match::class, 'add']);
  Route::put('/{match}', [Match::class, 'update']);
  Route::delete('/{match}', [Match::class, 'delete']);
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
  Route::delete('/{event}', [Event::class, 'delete']);
  Route::delete('/request/{eventReq}', [EventRequest::class, 'delete']);
});

Route::prefix('home')->middleware('auth:sanctum')->group(function() {
  Route::get('/data', function (Request $req) {
    $events = (new Event())->index();
    $matches = (new Match())->index($req, ">=,0");

    return ["matches" => $matches, "events" => $events];
  });
});
