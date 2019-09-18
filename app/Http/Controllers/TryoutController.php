<?php

namespace App\Http\Controllers;

use App\Tryout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TryoutController extends Controller
{
    protected $data = ["page_name" => "tryouts"];
    public function home(){
        $data = $this->data;
        $data['tryouts'] = Tryout::orderBy("id", "DESC")->get();
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
        $input = $req->all("title", "contents");

        $rules = [
            "title" => ["required", "max:50"],
            "contents" => "required"
        ];

        $errors = [
            "required" => "내용을 입력해 주세요.",
            "title.max" => "제목은 50자까지만 입력할 수 있습니다."
        ];

        $validator = Validator::make($input, $rules, $errors);

        if($validator->fails())
            return redirect()->route("tryouts.write")->withInput()->withErrors($validator);

        Tryout::create($input);
        return redirect()->route("tryouts.home")->with("flash_message", "새로운 일지가 작성되었습니다.");
    }
}
