<?php

namespace App\Http\Controllers;

use App\Practice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PracticeController extends Controller
{
    private $data;
    function __construct()
    {
        $this->data = ['page_name' => "practices"];
    }

    /**
     * 글 목록 보기
     */
    public function home()
    {
        return view("practices.home", $this->data);
    }

    /**
     * 글 쓰기
     */
    public function writePage()
    {
        return view("practices.write", $this->data);
    }
    public function insertPractice(Request $req)
    {
        $input = $req->only("title", "dev_start", "dev_end", "root");
        $thumbnail = $req->file("thumbnail");
        $execute_file = $req->file("execute_file");

        $rules = [
            "title" => "required|max:150",
            "dev_start" => "required|date_format:Y-m-d",
            "dev_end" => "required|date_format:Y-m-d",
            "root" => ["required", "regex:/^\\/.+\\.(php|html|js)$/"],
        ];

        $errors = [
            "required" => "내용을 입력해 주세요.",
            "title.max" => "제목은 150자까지만 입력할 수 있습니다.",
            "date_format" => "올바른 날짜를 입력해 주세요.",
            "root.regex" => "올바른 경로를 입력해 주세요."
        ];

        $validator = Validator::make($input, $rules, $errors);

        if($validator->fails()) return redirect()->route("practices.write")->withInput()->withErrors($validator);

        $input['created_no'] = $c_no = Practice::where("title", $input['title'])->count() + 1;

        if($c_no !== 1)
        {
            $input['saved_path'] = Practice::where("title", $input['title'])->first()->saved_path;
        }
        else
        {
            
        }

    }
}
