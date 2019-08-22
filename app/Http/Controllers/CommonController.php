<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    function __construct(){
        $this->data = ["page_name" => "home"];
    }

    public function index(){
        $data = $this->data;
        $projects = Project::orderBy("id", "DESC")->offset(0)->take(4)->get();
        foreach($projects as $project){
            $project->hash_tag = array_slice(explode("|", $project->hash_tag), 0, 4);
            $project->main_lang = explode("|", $project->main_lang)[0];
        }
        $data['projects'] = $projects;

        return view("index", $data);
    }
}
