<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\eventRequest as EventReqModel;
use App\Models\event as EventModel;
use App\Models\teamPlayer as TeamPlayerModel;
use App\Models\match as MatchModel;
use App\Http\Controllers\Event as EventController;
use App\Http\Controllers\Player as PlayerController;
use App\Models\User as UserModel;
use App\Models\team as TeamModel;

use Illuminate\Support\Facades\Auth;

class EventRequest extends Controller
{
  public function index($eventId)
  {
    $eventReqs = EventReqModel::where('event_id' , $eventId)->get();

    foreach($eventReqs as $eventReq) {
      $eventReq["creator"] = UserModel::wherekey($eventReq->creator_user_id)
          ->get()->first();
      $eventReq["team"] = TeamModel::wherekey($eventReq->team_id)
          ->get()->first();
    }

    return $eventReqs;
  }

  public function add(Request $req)
  {
    $req->validate([ 'event_id' => 'required' ]);

    $event = EventModel::query()->select()->whereKey($req->event_id)
      ->get()->first();

    if (
      $event->type == EventController::$TYPES['SEARCH_TEAM'] ||
      $event->type == EventController::$TYPES['CHALLENGE_TEAM']
    ) {
      $req->validate(['team_id' => 'required']);
    }

    EventReqModel::create([
      'event_id' => $req->event_id,
      'team_id' => $req->team_id,
      'creator_user_id' => Auth::user()->id
    ]);

    return response(["message" => __("messages.eventReqCreated")], 200);
  }

  public function delete(EventReqModels $eventReq)
  {
    $eventReq->delete();
    return response(null, 200);
  }

  public function acceptReq(Request $req, EventReqModel $eventReq) {
      $event = EventModel::query()->select()->whereKey($eventReq->event_id)
        ->get()->first();

      if($req->user()->id !== $event->creator_user_id)
          return response(null, 403 );

      switch($event->type) {
        case EventController::$TYPES['SEARCH_PLAYERS']:
          $player = (new PlayerController())->index($eventReq->creator_user_id)->first();
          TeamPlayerModel::firstOrCreate([
            'player_id' => $player->id,
            'team_id'=> $event->team_id
          ]);
          break;
        case EventController::$TYPES['SEARCH_TEAM']:
          $player = (new PlayerController())->index($event->creator_user_id);
          TeamPlayerModel::firstOrCreate([
            'player_id' => $player->id,
            'team_id'=> $eventReq->team_id
          ]);
          break;
        case EventController::$TYPES['CHALLENGE_TEAM']:
          MatchModel::create([
            'home_team_id' => $event->team_id,
            'away_team_id' => $eventReq->team_id,
            'location_long' => $event->location_long,
            'location_lat' => $event->location_lat,
            'address' => $event->address,
            'description' => $event->description,
            'schedule_time' => $event->schedule_time ?? today()->toString(),
            'start_time' => $event->schedule_time ?? today()->toString(),
            'creator_user_id' => $event->creator_user_id
          ]);
          break;
      }

    return response(["message" => __("messages.eventReqAccepted")], 200);
  }
}
