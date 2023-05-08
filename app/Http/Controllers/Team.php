<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\team as TeamModel;
use Illuminate\Support\Facades\Auth;
use App\Models\image as ImageModel;
use App\Models\teamPlayer as TeamPlayerModel;

class Team extends Controller
{

  public function index()
  {
    return TeamModel::query()
      ->select([
        'teams.id', 'name', 'location_long', 'location_lat', 'address',
        'teams.created_at', 'url as logo_img_url'
      ])->leftJoin('images', 'logo_img_id', '=', 'images.id')
      ->get();
  }

  public function add(Request $req, $creatorUserId = null)
  {
    $req->validate([
      'name' => 'required|unique:teams',
      'location_long' => 'required',
      'location_lat' => 'required'
    ]);

    $newTeam = TeamModel::create([
      'name' => $req->name,
      'location_long' => $req->location_long,
      'location_lat' => $req->location_lat,
      'address' => $req->address,
      'creator_user_id' => $creatorUserId ?? Auth::user()->id,
      'logo_img_id' => ImageModel::getImgIdByUrl($req->logo_img_url)
    ]);

    if(!empty($req->players)) {
        foreach($req->players as $player)
          TeamPlayerModel::firstOrCreate([
            "team_id" => $newTeam->id,
            "player_id" => $player
          ]);
    }

    return response(null, 200);
  }

  public function update(Request $req, TeamModel $team)
  {
    $team->updateOrFail($req->all());
    return response(null, 200);
  }

  public function delete(TeamModel $team)
  {
    $team->delete();
    return response(null, 200);
  }

  public function getPlayers($teamId)
  {
    return TeamPlayerModel::query()->select([
      'p.id', 'first_name', 'last_name', 'location_lat', 'location_long',
      'birthdate', 'dominate_foot', 'weight', 'height', 'year_active',
      'url as imgUrl'
    ])->join('players as p', 'player_id', '=', 'p.id')
      ->leftJoin('images', 'img_id', '=', 'images.id')
      ->where('team_id', '=', $teamId)
      ->get();

  }
}
