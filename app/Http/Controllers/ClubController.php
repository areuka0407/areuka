<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClubController extends Controller
{
    public function home($year = "2018"){
        $data['year'] = $year;
        $data['page_name'] = "clubs.home";
        return view("clubs.home", $data);
    }
}
