<?php

namespace App\Models;

use App\Http\Controllers\StadiumImg as StadiumImgController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stadium extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['imgs_urls'];

    protected $table = 'stadiums';

  public function getImgsUrlsAttribute() {
    $data = (new StadiumImgController())->index($this->attributes["id"]);
    return $data->map(function($el) {return $el["url"];});
  }

  public function owner() {
    return $this->belongsTo(\App\Models\User::class, "owner_user_id");
  }
}
