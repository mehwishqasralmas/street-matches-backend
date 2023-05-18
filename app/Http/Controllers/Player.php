<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\player as PlayerModel;
use App\Models\image as ImageModel;
use App\Models\teamPlayer as TeamPlayerModel;
use App\Models\User as UserModel;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\isEmpty;

class Player extends Controller
{
    public function index($userId = null) {
      $players = PlayerModel::query()->select();

      if(!empty($userId))
        $players = $players->where("user_id", "=", $userId);

      return $players->get();
    }

    public function add(Request $req, $userId = null, $creatorUserId = null) {
        $req->validate([
          'first_name' => 'required',
          'last_name' => 'required',
          'location_long' => 'required',
          'location_lat' => 'required',
          'position' => 'required'
        ]);

      $newPlayer = PlayerModel::create([
        'first_name' => $req->first_name,
        'last_name' => $req->last_name,
        'birthdate' => $req->birthdate,
        'location_long' => $req->location_long,
        'location_lat' => $req->location_lat,
        'address' => $req->address,
        'description' => $req->description,
        'user_id' => $userId,
        'dominate_foot' => $req->dominate_foot,
        'weight' => $req->weight,
        'position' => $req->position,
        'height' => $req->height,
        'year_active' => $req->year_active,
        'creator_user_id' => $creatorUserId ?? Auth::user()->id,
        'img_id' => ImageModel::getImgIdByUrl($req->img_url)
      ]);

     if(!isEmpty($req->team_id))
       $this->assignToTeam($newPlayer->id, $req->team_id);

      return response(["message" => __("messages.playerCreated")], 200);
    }

  public function update(Request $req, PlayerModel $player) {
      $player->updateOrFail($req->all());
      return response(null, 200);
  }

  public function delete(Request $req, PlayerModel $player) {
      $player->delete();
      return response(null, 200);
  }

  public function getTeams($playerId) {
    return TeamPlayerModel::query()->select([
      't.id', 'name', 't.location_long', 't.location_lat', 't.address',
      't.created_at', 'url as logo_img_url'
    ])->join('teams as t', 'team_id', '=', 't.id')
      ->leftJoin('images', 'logo_img_id', '=', 'images.id')
      ->where('players.id', '=', $playerId)
      ->get();
  }

  public function assignToTeam($playerId, $teamId) {
    TeamPlayerModel::firstOrCreate([
      'player_id' => $playerId,
      'team_id'=> $teamId
    ]);
    return response(null, 200);
  }

  public function unassignFromTeam($playerId, $teamId) {
    TeamPlayerModel::where('player_id', $playerId)
      ->where('team_id', $teamId)
      ->delete();
    return response(null, 200);
  }

  public function getPlayerPostions() {
      return PlayerModel::$POSTIONS;
  }

  public function getDetails(PlayerModel $player) {
    if(!empty($player->user_id))
        $player->user = UserModel::query()->select()->whereKey($player->user_id);
  }
}
