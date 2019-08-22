<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TryoutController extends Controller
{
    public function home(){
        $data['page_name'] = "tryouts.home";
        return view("tryouts.home", $data);
    }
}
