<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\image as ImageModel;

class team extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['logo_img_url'];

    public function getLogoImgUrlAttribute() {
      return ImageModel::getImgUrlById($this->attributes["logo_img_id"]);
    }
}
