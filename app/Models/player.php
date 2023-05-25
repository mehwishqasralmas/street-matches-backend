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
    protected $appends = ['position_name', "position_name_cn", "img_url"];

    public static $POSTIONS = [
      ["code" => 'GK', "name"=> "Goalkeeper", "name_cn" => "守门人"],
      ["code" => 'DEF_CB', "name"=> "Centre-Back Defender", "name_cn" => "中后卫"],
      ["code" => 'DEF_SW', "name"=> "Sweeper Defender", "name_cn" => "扫荡者后卫"],
      ["code" => 'DEF_FB', "name"=> "Full-Back Defender", "name_cn" => "全后卫"],
      ["code" => 'MID_CM', "name"=> "Central Midfielder", "name_cn" => "中场球员"],
      ["code" => 'MID_DM', "name"=> "Defensive Midfielder", "name_cn" => "防守型中场"],
      ["code" => 'MID_ATK', "name"=> "Attacking Midfielder", "name_cn" => "攻击性中场"],
      ["code" => 'FW_SS', "name"=> "Second Striker Forward", "name_cn" => "第二前锋前卫"],
      ["code" => 'FW_CF', "name"=> "Centre Forward", "name_cn" => "中前卫"],
      ["code" => 'FW_W', "name"=> "Winger Forward", "name_cn" => "边锋 前锋"]
    ];

    public function getPositionNameAttribute() {
      $position = $this->attributes['position'];
      return static::getPositionNameFromCode($position, 'cn');
    }

  public function getPositionNameCnAttribute() {
    $position = $this->attributes['position'];
    return static::getPositionNameFromCode($position);
  }

  public function getImgUrlAttribute() {
    return ImageModel::getImgUrlById($this->attributes["img_id"]);
  }

  public static function getPositionNameFromCode($code, $lang = "en") {
    foreach (static::$POSTIONS as $pos) {
      if($code == $pos['code'])
        return $lang == "en" ? $pos['name'] : $pos['name_cn'];
    }

    return null;
  }

}
