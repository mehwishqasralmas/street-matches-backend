<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\mtch as MatchModel;
use App\Models\team as TeamModel;
use Illuminate\Support\Facades\Auth;
use App\Models\image as ImageModel;
use App\Http\Controllers\Team as TeamController;

class Mtch extends Controller
{

  public function index (
    Request $req,
    $dayOffsetFilter = null,
    $matchId = null,
    $teamId = null,
    $onlyOwn = null,
    $userId = null
  )
  {

    $dayOffsetFilter = $dayOffsetFilter ?? $req->query("dayOffsetFilter");
    $onlyOwn = $onlyOwn ?? $req->query("onlyOwn");
    $limit = $req->query('limit');
    $userId = $userId ?? $req->query('userId');
    $daysOffset = null;
    $daysOffsetOp = null;

    if(!empty($dayOffsetFilter || $dayOffsetFilter == "0")) {
      $dayOffsetFilter = explode(",", $dayOffsetFilter);
      $daysOffsetOp = count($dayOffsetFilter) > 1 ? $dayOffsetFilter[0] : "=";
      $daysOffset = $dayOffsetFilter[1] ?? $dayOffsetFilter[0];
    }

    $matches = MatchModel::query()->select();
    $matches = !empty($matchId) ? $matches->whereKey($matchId) : $matches;
    $matches = !empty($teamId) ? $matches
      ->whereRaw("(home_team_id = $teamId OR away_team_id = $teamId)") :
      $matches;

    if(!empty($daysOffset) || $daysOffset == "0")
      $matches = $matches->whereRaw (
        "DATE(schedule_time) $daysOffsetOp DATE_ADD(CURRENT_DATE(), INTERVAL $daysOffset DAY)"
      );

    if(!empty($onlyOwn) && !empty($req->user()))
      $matches = $matches->where("creator_user_id", $req->user()->id);
    else if(!empty($userId))
      $matches = $matches->where('creator_user_id', $userId);


    $matches = $matches->orderByDesc('schedule_time')->limit($limit)->get();

    foreach ($matches as $match) {
      $homeTeam = TeamModel::find($match->home_team_id);
      $awayTeam = TeamModel::find($match->away_team_id);

      $homeTeam['logo_img_url'] = ImageModel::getImgUrlById($homeTeam['logo_img_id']);
      $awayTeam['logo_img_url'] = ImageModel::getImgUrlById($awayTeam['logo_img_id']);

      $match['home_team'] = $homeTeam;
      $match['away_team'] = $awayTeam;
    }

    if(!empty($matchId) && $matches->isNotEmpty()) {
      $match = $matches->get(0);
      $match['home_team']['players'] =
        (new TeamController())->getPlayers($homeTeam->id);
      $match['away_team']['players'] =
        (new TeamController())->getPlayers($awayTeam->id);
      return $match;
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
      'description' => $req->description,
      'description_cn' => $req->description_cn,
      'schedule_time' => $req->schedule_time,
      'start_time' => $req->start_time,
      'creator_user_id' => Auth::user()->id
    ]);

    return response(["message" => __("messages.matchCreated")], 200);
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
