<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        "title",
        "hash_tag",
        "description",
        "main_lang",
        "saved_folder",
        "back_color",
        "thumbnail",
        "font-color",
        "root",
        "dev_start",
        "dev_end"
    ];
}
