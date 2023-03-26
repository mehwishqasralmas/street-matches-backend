<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User;
use App\Http\Controllers\Player;
use App\Http\Controllers\Team;
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
});

Route::prefix('team')->middleware('auth:sanctum')->group(function() {
  Route::get('/list', [Team::class, 'index']);
  Route::get('/{team}/players', [Team::class, 'getPlayers']);
  Route::post('/', [Team::class, 'add']);
  Route::put('/{team}', [Team::class, 'update']);
  Route::delete('/{team}', [Team::class, 'delete']);
});
