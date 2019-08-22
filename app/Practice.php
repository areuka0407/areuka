<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Practice extends Model
{
    protected $fillable = [
        "title", "created_no", "saved_folder", "thumbnail", "dev_start", "dev_end", "root"
    ];
}
