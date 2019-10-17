<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{
    public function load(Request $req, $table){
        $condition = $req->all();
        $data = DB::table($table)->where($condition)->get();
        return response()->json($data);
    }
}