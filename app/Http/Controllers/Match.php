<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\match as MatchModel;
use App\Models\team as TeamModel;
use Illuminate\Support\Facades\Auth;

class Match extends Controller
{

  public function index()
  {
    $matches = MatchModel::all();

    foreach ($matches as $match) {
      $homeTeam = TeamModel::find($match->home_team_id);
      $awayTeam = TeamModel::find($match->away_team_id);

      $match['home_team'] = $homeTeam;
      $match['away_team'] = $awayTeam;
    }

    return $matches;
  }

  public function add(Request $req)
  {
    $req->validate([
      'home_team_id' => 'required',
      'away_team_id' => 'required',
      'location_long' => 'required',
      'location_lat' => 'required',
      'schedule_time' => 'required'
    ]);

    MatchModel::create([
      'home_team_id' => $req->home_team_id,
      'away_team_id' => $req->away_team_id,
      'location_long' => $req->location_long,
      'location_lat' => $req->location_lat,
      'address' => $req->address,
      'schedule_time' => $req->schedule_time,
      'start_time' => $req->start_time,
      'creator_user_id' => Auth::user()->id
    ]);

    return response(null, 200);
  }

  public function update(Request $req, MatchModel $match)
  {
    $match->updateOrFail($req->all());
    return response(null, 200);
  }

  public function delete(MatchModel $match)
  {
    $match->delete();
    return response(null, 200);
  }
}
