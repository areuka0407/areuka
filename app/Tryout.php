<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tryout extends Model
{
    protected $fillable = [
        "title",
        "contents",
        "w_date",
        "v_count"
    ];
}
