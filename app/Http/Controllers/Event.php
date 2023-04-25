<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\event as EventModel;
use Illuminate\Support\Facades\Auth;

class Event extends Controller
{
  static $TYPES = [
    'SEARCH_PLAYERS' => 'SEARCH_PLAYERS',
    'SEARCH_TEAM' => 'SEARCH_TEAM',
    'CHALLENGE_TEAM' => 'CHALLENGE_TEAM'
  ];

  public function index()
  {
    $events = EventModel::all();

    foreach ($events as &$event) {
      if($event->type == static::$TYPES['SEARCH_PLAYERS']) {
        $positions = explode(',', $event['players_positions']);
        $counts = explode(',', $event['players_cnts']);
        $temp = ['positions' => []];

        for ($indx = 0; $indx < count($positions); ++$indx) {
          $temp['positions'][] = [
            "position" => $positions[$indx],
            "count" => $counts[$indx]
          ];
        }
        $event['positions'] = array_merge($temp);
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
      'location_long' => 'required',
      'location_lat' => 'required',
      'description' => 'required'
    ]);
    $playersPos = null;
    $playersCnt = null;

    if($req->type == static::$TYPES['CHALLENGE_TEAM']) {
      $req->validate(['team_id' => 'required']);
    } else if($req->type == static::$TYPES['SEARCH_PLAYERS']) {
      $req->validate(['positions' => 'required']);
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
      'description' => $req->description,
      'players_positions' => $playersPos,
      'players_cnts' => $playersCnt,
      'team_id' => $req->team_id,
      'creator_user_id' => Auth::user()->id,
    ]);

    return response(null, 200);
  }

  public function delete(EventModel $event)
  {
    $event->delete();
    return response(null, 200);
  }
}
