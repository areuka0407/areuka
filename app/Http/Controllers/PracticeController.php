<?php

namespace App\Http\Controllers;

use App\Practice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    public function home($year = 2018)
    {
        $data = $this->data;

        $data['condition'] = $condition = (object)[
            "year" => $year,
            "category" => isset($_GET['category']) ? "%".$_GET['category']."%" : "%_%",
        ];


        $order = explode("-", isset($_GET['order']) ? $_GET['order'] : "title-ASC");
        $data['order'] = $order = (object)["key" => $order[0], "direction" => $order[1]];

        $filter = [
            ["dev_start", "LIKE", $year."%"],
            ["title", "LIKE", $condition->category]
        ];

        $subTable = "SELECT title, COUNT(*) AS cnt FROM practices AS p GROUP BY title";
        $data['categories'] = DB::select("SELECT DISTINCT p.title, p.dev_start, c.cnt FROM practices AS p LEFT JOIN ($subTable) AS c ON c.title = p.title WHERE p.dev_start LIKE ?", [$year. "%"]);
        $data['projects'] = Practice::where($filter)->orderBy($order->key, $order->direction)->get();
        return view("practices.home", $data);
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

        $sameTitle = Practice::where("title", $input['title']);
        $input['created_no'] = $c_no = $sameTitle->count() + 1;

        if($c_no !== 1) $saved_folder = $input['saved_folder'] = $sameTitle->first()->saved_folder;
        else $saved_folder = $input['saved_folder'] = c_mkdir("Practices");

        /* 섬네일 이미지 체크 */

        // 이미지 파일 제한
        if(strncmp($thumbnail->getClientMimeType(), "image", 5) !== 0){
            return redirect()->route("projects.write")->withInput()->withErrors(["thumbnail" => "이미지 파일만 업로드 할 수 있습니다."]);
        }
        // 이미지 용량 제한
        if($thumbnail->getClientSize() > 1024 * 1024 * 2){
            return redirect()->route("projects.write")->withInput()->withErrors(["thumbnail" => "최대 2MB까지만 업로드 가능합니다."]);
        }
        // 이미지 확장자 제한
        $exts = ["jpg", "png", "jpeg", "gif"];
        if(!in_array(strtolower($thumbnail->getClientOriginalExtension()), $exts)){
            return redirect()->route("projects.write")->withInput()->withErrors(["thumbnail" => ".jpg, .jpeg, .png, .gif 확장자 파일만 업로드할 수 있습니다."]);
        }

        /* 실행 파일 검사 */

        // zip 확장자 검사
        if(strtolower($execute_file->getClientOriginalExtension()) !== "zip"){
            return redirect()->route("projects.write")->withInput()->withErrors(["execute_file" => "zip 형식 압축 파일만 업로드할 수 있습니다."]);
        }

        $input['thumbnail'] = "thumbnail." . $thumbnail->getClientOriginalExtension();

        $thumbnail->move(practice_path($saved_folder, $c_no), "thumbnail.".$thumbnail->getClientOriginalExtension());
        $execute_file->move(practice_path($saved_folder, $c_no), "compress.".$execute_file->getClientOriginalExtension());

        Practice::create($input);
        return redirect()->route("practices.home")->with("flash_message", "연습 내용을 저장했습니다.");
    }
}
