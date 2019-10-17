<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JoinRequest extends Model
{
    protected $fillable = [
        "user_id", "password", "user_email", "user_name"
    ];
}
