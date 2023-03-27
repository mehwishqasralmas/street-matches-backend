<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\lineup as LineupModel;

class Lineup extends Controller
{
  public function index(Request $req, $matchId, $teamId = null)
  {
    $teamId = $teamId ?? $req->query('team_id');
    $lineups = LineupModel::query()
      ->select([
        'team_id', 'player_id', 'position', 'shirt_number',
        'first_name', 'last_name', 'location_lat', 'location_long',
        'birthdate', 'dominate_foot', 'weight', 'height', 'year_active',
        'url as imgUrl'
      ])->join('players as p', 'player_id', '=', 'p.id')
      ->leftjoin('images as i', 'p.img_id', '=', 'i.id')
      ->where('match_id', '=', $matchId);

    if(!empty($teamId))
      $lineups = $lineups->where('team_id', '=', $teamId);

    $lineups = $lineups->get();
    $res = [];

    foreach ($lineups as $lineup) {
      if(empty($res[$lineup->team_id])) {
        $res[$lineup->team_id] = [
          "team_id" => $lineup->team_id,
          "players" => []
        ];
      }
      $res[$lineup->team_id]["players"][] = [
        'player_id' => $lineup->player_id,
        'position' => $lineup->position,
        'shirt_number' => $lineup->shirt_number,
        'first_name' => $lineup->first_name,
        'last_name' => $lineup->last_name,
        'location_lat' => $lineup->location_lat,
        'location_long' => $lineup->location_long,
        'birthdate' => $lineup->birthdate,
        'dominate_foot' => $lineup->dominate_foot,
        'weight' => $lineup->weight,
        'height' => $lineup->height,
        'year_active' => $lineup->year_active,
        'imgUrl' => $lineup->imgUrl
      ];
    }

    return array_values($res);
  }

  public function add(Request $req)
  {
    $req->validate([
      'team_id' => 'required',
      'players' => 'required'
    ]);

    $lineups = [];
    foreach ($req->players as $player) {
      $lineups[] = [
        'team_id' => $req->team_id,
        'match_id' => $req->match_id,
        'player_id' => $player['player_id'],
        'position' => $player['position'],
        'shirt_number' => $player['shirt_number']
      ];
    }


    LineupModel::insert($lineups);

    return response(null, 200);
  }

  public function delete($matchId, $teamId = null)
  {
    $query = LineupModel::where('match_id', $matchId);

    if(!empty($teamId))
      $query->where('team_id', $teamId);

    $query->delete();

    return response(null, 200);
  }
}
