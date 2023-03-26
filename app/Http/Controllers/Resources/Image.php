<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\image as ImageModel;

class Image extends Controller
{
    public function upload(Request $req) {
      $uploadedImg = $req->file('img');
      $path = $uploadedImg->storeAs(
        'imgs',
        now()->timestamp . "_" . $uploadedImg-> getClientOriginalName(),
        'imgs'
      );

      $newImg = new ImageModel();
      $newImg->url = $path;
      $newImg->save();

      return response(['path' => $path], 200);
    }
}
