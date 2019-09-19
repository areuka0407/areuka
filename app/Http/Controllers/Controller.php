<?php

namespace App\Http\Controllers;

use App\Project;
use App\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $data = [];
    function __construct(){
        $this->data['main_projects'] = [
            "year_2018" => Project::where("dev_start", "LIKE" ,"2018%")->get(),
            "year_2019" => Project::where("dev_start", "LIKE" ,"2019%")->get(),
            "year_2020" => Project::where("dev_start", "LIKE" ,"2020%")->get()
        ];

        if(!User::where("user_id", "areuka")->first()){
            $user_data = [
                "user_id" => "areuka",
                "password" => bcrypt("rudwlstkfkd0407"),
                "user_email" => "areuka0407@gmail.com",
                "user_name" => "Areuka",
                "auth" => "1"
            ];
            User::create($user_data);
        }
    }
}
