<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mtch extends Model
{
    use HasFactory;

    protected $table = "matches";
    protected $guarded = ['id'];
}
