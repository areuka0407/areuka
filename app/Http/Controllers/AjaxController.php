<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{
    public function load(Request $req){
        $s_table = $req->get("table");
        $data = DB::table($s_table)->get();
        return response()->json($data);
    }
}
