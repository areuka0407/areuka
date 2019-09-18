<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TryoutController extends Controller
{
    protected $data = ["page_name" => "tryouts"];
    public function home(){
        $data = $this->data;
        return view("tryouts.home", $data);
    }

    /**
     * 글 쓰기
     */
    public function writePage(){
        $data = $this->data;
        return view("tryouts.write", $data);
    }
    public function insertTryout(Request $req){
        dd($req->all());
    }
}
