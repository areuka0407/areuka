<?php

namespace App\Http\Controllers;

use App\Project;
use App\Session;
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
        /* Footer Projects 자세히보기 데이터 */
        $this->data['main_projects'] = [
            "year_2018" => Project::where("dev_start", "LIKE" ,"2018%")->limit(4)->get(),
            "year_2019" => Project::where("dev_start", "LIKE" ,"2019%")->limit(4)->get(),
            "year_2020" => Project::where("dev_start", "LIKE" ,"2020%")->limit(4)->get()
        ];

        /* DB에 관리자 아이디가 없으면 자동 추가 */
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
