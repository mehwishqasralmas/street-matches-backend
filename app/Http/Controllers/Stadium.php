<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stadium AS StadiumModel;
use App\Models\image as ImageModel;
use App\Http\Controllers\StadiumImg as StadiumdImgController;
class Stadium extends Controller
{
    public function index(Request $req, $onlyOwn = null, $userId = null) {
        $onlyOwn = $onlyOwn ?? $req->query("onlyOwn");
        $data = StadiumModel::query()->select();
        $userId = $userId ?? $req->query('userId');

        if(!empty($userId))
          $data = $data->where('owner_user_id', $userId);
        else if(!empty($onlyOwn) && !empty($req->user('sanctum'))) {
          $curUserId = $req->user('sanctum')->id;
          $data = $data->where('owner_user_id', $curUserId);
        }


        return $data->with('owner')->get();
    }

  public function details (Request $req, StadiumModel $stadium) {
    return $stadium;
  }

  public function add(Request $req) {
      $data = $req->all();
      $data["owner_user_id"] = $req->user()->id;
      $imgsIds = [];

      if(!empty($data["imgs_urls"]))
        foreach ($data["imgs_urls"] as $imgUrl) {
          array_push($imgsIds, ImageModel::getImgIdByUrl($imgUrl));
        }

      $newStadium = StadiumModel::create($data);
      (new StadiumdImgController())->reset($newStadium, $imgsIds);

      return ["message" => "success"];
  }

  public function delete(Request $req, StadiumModel $stadium) {
    $stadium->delete();
    return ["message" => "success"];
  }

}
