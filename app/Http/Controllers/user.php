<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User as UserModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class User extends Controller
{

    public function logIn(Request $req) {
        $cred = $req->validate([
           'email' => 'required',
           'password' => 'required'
        ]);

        if(Auth::attempt($cred)) {
          $user = Auth::user();
          $token = $user->createToken($user->first_name)->plainTextToken;
          $user->token = $token;
          return response($user);
        }

        return response(null, 401);
    }


  public function user(Request $req) {

    if(Auth::check()) {
      $user = Auth::user();
      return response($user);
    }

    return response(null, 401);
  }
}
