<?php

namespace App\Models;

use App\Models\image as ImageModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stadium extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['img_url'];

    protected $table = 'stadiums';

  public function getImgUrlAttribute() {
    return ImageModel::getImgUrlById($this->attributes["img_id"]);
  }
}
