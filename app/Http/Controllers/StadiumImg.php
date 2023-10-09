<?php

namespace App\Http\Controllers;
use App\Models\StadiumImg AS StadiumImgModel;
use App\Models\Stadium AS StadiumModel;
use Illuminate\Http\Request;

class StadiumImg extends Controller
{
    public function index($stadiumId = null) {

      $data = StadiumImgModel::query()->select();
      if(!empty($stadiumId))
        $data = $data->where('stadium_id', $stadiumId);

      return $data->get();
    }

    public function reset (StadiumModel $stadium, $imgsIds) {
      $data = [];

      forEach($imgsIds as $imgId) {
        $data[] = ["stadium_id" => $stadium->id, "img_id" => $imgId];
      }

      StadiumImgModel::where("stadium_id", $stadium->id)->delete();
      StadiumImgModel::insert($data);
    }

}
