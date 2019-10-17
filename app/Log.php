<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        "ip_address", "uid", "logged_at"
    ];
}
