<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stadium AS StadiumModel;
use App\Models\image as ImageModel;

class Stadium extends Controller
{
    public function index(Request $req, $onlyOwn = null) {
        $onlyOwn = $onlyOwn ?? $req->query("onlyOwn");
        $data = StadiumModel::query()->select();

        if(!empty($onlyOwn)) {
          $curUserId = $req->user('sanctum')->id;
          $data = $data->where('owner_user_id', $curUserId);
        }


        return $data->get();
    }

  public function details (Request $req, StadiumModel $stadium) {
    return $stadium;
  }

  public function add(Request $req) {
      $data = $req->all();
      $data["owner_user_id"] = $req->user()->id;
      $data["img_id"] = ImageModel::getImgIdByUrl($req->img_url);

      StadiumModel::create($data);
      return ["message" => "success"];
  }

  public function delete(Request $req, StadiumModel $stadium) {
    $stadium->delete();
    return ["message" => "success"];
  }

}
