<?php

namespace App\Models;

use App\Models\image as ImageModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class player extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $hidden = ['img_id'];
    protected $appends = ['position_name', "img_url"];

    public static $POSTIONS = [
      ["code" => 'GK', "name"=> "Goalkeeper"],
      ["code" => 'DEF_CB', "name"=> "Centre-Back Defender"],
      ["code" => 'DEF_SW', "name"=> "Sweeper Defender"],
      ["code" => 'DEF_FB', "name"=> "Full-Back Defender"],
      ["code" => 'MID_CM', "name"=> "Central Midfielder"],
      ["code" => 'MID_DM', "name"=> "Defensive Midfielder"],
      ["code" => 'MID_ATK', "name"=> "Attacking Midfielder"],
      ["code" => 'FW_SS', "name"=> "Second Striker Forward"],
      ["code" => 'FW_CF', "name"=> "Centre Forward"],
      ["code" => 'FW_W', "name"=> "Winger Forward"]
    ];

    public function getPositionNameAttribute() {
      $position = $this->attributes['position'];
      foreach (static::$POSTIONS as $pos) {
        if($position == $pos['code'])
          return $pos['name'];
      }

      return null;
    }

  public function getImgUrlAttribute() {
    return ImageModel::getImgUrlById($this->attributes["img_id"]);
  }

}
