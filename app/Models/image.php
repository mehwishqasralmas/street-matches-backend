<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class image extends Model
{
    use HasFactory;

    public static function getImgUrlById($id) {
        if(empty($id)) return null;
        $img = static::where('id', $id)->first();
        return empty($img) ? null : $img->url;
    }

    public static function getImgIdByUrl($url) {
      if(empty($url)) return null;
      $img = static::where('url', $url)->first();
      return empty($img) ? null : $img->id;
    }

}
