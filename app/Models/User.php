<?php

namespace App\Models;

use Illuminate\Contracts\Auth\CanResetPassword as PasswordReset;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Auth\Passwords\CanResetPassword;
use App\Models\image as ImageModel;

class User extends Authenticatable implements PasswordReset
{
    use HasApiTokens, HasFactory, Notifiable, CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'img_id',
      'email_verified_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ["img_url"];

    public function setPasswordAttribute($value) {
      $this->attributes['password'] = password_hash($value, null);
    }

    public function getImgUrlAttribute() {
      return ImageModel::getImgUrlById($this->attributes["img_id"]);
    }

    public function player() {
      return $this->hasOne(\App\Models\player::class);
    }
}
