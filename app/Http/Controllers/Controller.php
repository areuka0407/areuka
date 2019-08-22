<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    function __construct(){
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
