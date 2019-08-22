<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function home(){
        return view("bookmarks.home");
    }
}
