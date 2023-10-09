<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\event as EventModel;
use Illuminate\Support\Facades\Auth;
use App\Models\image as ImageModel;
use App\Models\player as PlayerModel;
use DB;

class Event extends Controller
{
  static $TYPES = [
    'SEARCH_PLAYERS' => 'SEARCH_PLAYERS',
    'SEARCH_TEAM' => 'SEARCH_TEAM',
    'CHALLENGE_TEAM' => 'CHALLENGE_TEAM'
  ];

  public function index(Request $req, $onlyOwn = null, $userId = null)
  {
    $onlyOwn = $onlyOwn ?? $req->query('onlyOwn');
    $limit = $req->query('limit');
    $userId = $userId ?? $req->query('userId');

    $events = EventModel::query()
      ->select('events.*',
        'teams.name AS team_name',
        'teams.name_cn AS team_name_cn',
        'teams.address AS team_address',
        'event_img.url AS event_img_url',
        'team_img.url AS team_logo_url',
        DB::raw("CONCAT(first_name, ' ', last_name) AS creator_name"),
        'user_img.url AS creator_img_url')
      ->join('users', 'creator_user_id', '=', 'users.id')
      ->leftJoin('teams', 'team_id', '=', 'teams.id')
      ->leftJoin('images AS event_img', 'events.img_id', '=', 'event_img.id')
      ->leftJoin('images AS team_img', 'teams.logo_img_id', '=', 'team_img.id')
      ->leftJoin('images AS user_img', 'users.img_id', '=', 'user_img.id')
      ->orderByDesc('events.created_at');

    if(!empty($onlyOwn) && !empty($req->user('sanctum')))
      $events = $events->where('events.creator_user_id', $req->user('sanctum')->id);
    else if(!empty($userId))
      $events = $events->where('events.creator_user_id', $userId);

    $events = $events->limit($limit)->get();

    foreach ($events as &$event) {
      $event['event_img_url'] = ImageModel::fixImgUrl($event['event_img_url']);
      $event['creator_img_url'] = ImageModel::fixImgUrl($event['creator_img_url']);
      $event['team_logo_url'] = ImageModel::fixImgUrl($event['team_logo_url']);

      if($event->type == static::$TYPES['SEARCH_PLAYERS']) {
        $positions = explode(',', $event['players_positions']);
        $counts = explode(',', $event['players_cnts']);
        $temp = ['positions' => []];

        for ($indx = 0; $indx < count($positions); ++$indx) {

          if(empty($positions[$indx]))
            continue;

          $temp['positions'][] = [
            "position" => $positions[$indx],
            "position_name" => PlayerModel::getPositionNameFromCode($positions[$indx]),
            "position_name_cn" => PlayerModel::getPositionNameFromCode($positions[$indx], 'cn'),
            "count" => $counts[$indx]
          ];
        }
        $event['positions'] = $temp['positions'];
        unset($event['players_positions']);
        unset($event['players_cnts']);
      }
    }

    return $events;
  }

  public function add(Request $req)
  {
    $req->validate([
      'type' => 'required',
      'description' => 'required'
    ]);
    $playersPos = null;
    $playersCnt = null;

    if($req->type == static::$TYPES['CHALLENGE_TEAM']) {
        $req->validate([
          'team_id' => 'required',
          'schedule_time' => 'required'
        ]);
    }

    else if($req->type == static::$TYPES['SEARCH_PLAYERS']) {
      $req->validate(['team_id' => 'required', 'positions' => 'required']);
      $playersPos = $playersCnt = '';
      foreach ($req->positions as $position) {
        $playersPos = $playersPos . $position['position'] . ',';
        $playersCnt = $playersCnt . $position['count'] . ',';
      }
    }

    EventModel::create([
      'type' => $req->type,
      'location_long' => $req->location_long,
      'location_lat' => $req->location_lat,
      'address' => $req->address,
      'description' => $req->description,
      'description_cn' => $req->description_cn,
      'players_positions' => $playersPos,
      'players_cnts' => $playersCnt,
      'team_id' => $req->team_id,
      'schedule_time' => $req->schedule_time,
      'img_id' => ImageModel::getImgIdByUrl($req->img_url),
      'creator_user_id' => Auth::user()->id
    ]);

    return response(["message" => __("messages.eventCreated")], 200);
  }

  public function delete(EventModel $event)
  {
    $event->delete();
    return response(null, 200);
  }
}
