<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\image as ImgModel;

class stadiumImg extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['url'];

  public function getUrlAttribute() {
    return ImgModel::getImgUrlById($this->attributes["img_id"]);
  }
}
