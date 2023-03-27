<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\eventRequest as EventReqModel;
use Illuminate\Support\Facades\Auth;

class EventRequest extends Controller
{
  public function index($eventId)
  {
    return EventReqModel::where('event_id' , $eventId)->get();
  }

  public function add(Request $req)
  {
    $req->validate([
      'event_id' => 'required'
    ]);

    EventReqModel::create([
      'event_id' => $req->event_id,
      'creator_user_id' => Auth::user()->id
    ]);

    return response(null, 200);
  }

  public function delete(EventReqModels $eventReq)
  {
    $eventReq->delete();
    return response(null, 200);
  }
}
