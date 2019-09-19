<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->data["page_name"] = "projects";
    }

    public function home($year = 2018)
    {
        if(!auth()->user()) return redirect()->route("users.login")->with("flash_message", LOGIN_MESSAGE);
        $data = $this->data;

        $data['condition'] = $condition = (object)[
            "year" => $year,
            "category" => isset($_GET['category']) ? "%".$_GET['category']."%" : "%_%",
            "keyword" => isset($_GET['keyword']) ? "%".$_GET['keyword']."%" : "%_%"
        ];


        /* 정렬 유효성 검사 */
        $order = explode("-", isset($_GET['order']) ? $_GET['order'] : "title-ASC");
        $order = count($order) !== 2 ? "title-ASC" : $order;
        $order[0] = strtolower($order[0]);
        $order[1] = strtoupper($order[1]);
        $data['order'] = $order = (object)["key" => isset($order[0]) ? $order[0] : "title", "direction" => isset($order[1]) && ($order[1] === "ASC"||$order[1] === "DESC") ? $order[1] : "ASC"];
        $order->key = !in_array($order->key, Project::getAttrList()) ? "title" : $order->key;

        $filter = [
            ["dev_start", "LIKE", $year."%"],
            ["main_lang", "LIKE", $condition->category],
            ["title", "LIKE", $condition->keyword]
        ];

        $data['categories'] = DB::select("SELECT main_lang AS lang, COUNT(*) AS cnt FROM projects WHERE dev_start LIKE ? AND title LIKE ? GROUP BY main_lang", [$year. "%", $condition->keyword]);
        $data['projects'] = Project::where($filter)->orderBy($order->key, $order->direction)->get();

        return view("projects.home", $data);
    }

    public function writePage()
    {
        $data = $this->data;
        if(!admin()) return redirect()->route("projects.home")->with("flash_message", AUTH_MESSAGE);
        return view("projects.write", $data);
    }

    public function insertProject(Request $req)
    {
        if(!admin()) return redirect()->route("projects.home")->with("flash_message", AUTH_MESSAGE);
        $data = $req->only("title", "main_lang", "description", "back_color", "font_color", "hash_tag", "root", "dev_start", "dev_end");
        $thumbnail = $req->file("thumbnail");
        $execute_file = $req->file("execute_file");


        $date['dev_start'] = date("Y-m-d", strtotime($data['dev_start']));
        $date['dev_end'] = date("Y-m-d", strtotime($data['dev_end']));

        /* 파일을 제외한 모든 입력값 검사 */
        $rules = [
            "title" => ["required", "max:150"],
            "description" => "required",
            "main_lang" => "required",
            "back_color" => ["required", "regex:/^#[a-fA-F0-9]{0,6}$/"],
            "font_color" => ["required", "regex:/^#[a-fA-F0-9]{0,6}$/"],
            "hash_tag" => "required",
            "root" => ["required", "regex:/^\\/.+\\.(js|php|html)$/"],
            "dev_start" => ["required", "date_format:Y-m-d"],
            "dev_end" => ["required", "date_format:Y-m-d"],
        ];

        $errors = [
            "required" => "내용을 입력해 주세요.",
            "title.max" => "제목은 150자까지 작성할 수 있습니다.",
            "back_color.regex" => "올바른 색상을 입력해 주세요.",
            "font_color.regex" => "올바른 색상을 입력해 주세요.",
            "root.regex" => "올바른 파일 경로를 설정해 주세요.",
            "date_format" => "올바른 날짜를 입력해 주세요.",
        ];

        $validator = Validator::make($data, $rules, $errors);
        if($validator->fails())
            return redirect()->route("projects.write")->withInput()->withErrors($validator)->withInput();


        if(count(Project::where("title", $data['title'])->get()) !== 0){
            return redirect()->route("projects.write")->withInput()->withErrors(["title" => "해당 이름의 프로젝트가 이미 존재합니다."]);
        }

        $data['saved_folder'] = $saved_folder = c_mkdir("Projects");

        /* 섬네일 이미지 체크 */
        if($thumbnail){
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
            $data['thumbnail'] = "thumbnail.". $thumbnail->getClientOriginalExtension();
            $thumbnail->move(project_path($saved_folder), "thumbnail.".$thumbnail->getClientOriginalExtension());
        }
        else {
            copy(public_path("assets/images/non-image.png"), public_path("files/Projects/$saved_folder/thumbnail.png"));
            $data['thumbnail'] = "thumbnail.png";
        }


        /* 실행 파일 검사 */
        if($execute_file){
            // zip 확장자 검사
            if(strtolower($execute_file->getClientOriginalExtension()) !== "zip"){
                return redirect()->route("projects.write")->withInput()->withErrors(["execute_file" => "zip 형식 압축 파일만 업로드할 수 있습니다."]);
            }
            $execute_file->move(project_path($saved_folder), "compress.".$execute_file->getClientOriginalExtension());
        }

        Project::create($data);
        return redirect()->route("projects.home");
    }
    public function viewPage($id)
    {
        if(!auth()->user()) return redirect()->route("users.login")->with("flash_message", LOGIN_MESSAGE);
        $data = $this->data;
        $data['project'] = $project = Project::find($id);
        if(!$data['project']) return redirect()->route("projects.home")->with("flash_message", not_find_message("프로젝트"));
        $data['project']->main_lang = explode("|", $data['project']->main_lang);
        $data['project']->hash_tag = explode("|", $data['project']->hash_tag);
        return view("projects.view", $data);
    }


    public function rewritePage($id)
    {
        if(!admin()) return redirect()->route("projects.home")->with("flash_message", AUTH_MESSAGE);

        $data = $this->data;
        $data['project'] = Project::find($id);
        if(!$data['project']) return redirect()->route("projects.home")->with("flash_message", not_find_message("프로젝트"));
        $data['project']->main_lang = explode("|", $data['project']->main_lang);
        $data['project']->hash_tag = explode("|", $data['project']->hash_tag);

        return view("projects.rewrite", $data);
    }
    public function updateProject(Request $req, $id)
    {
        if(!admin()) return redirect()->route("projects.rewrite", [$id])->with("flash_message", "권한이 없습니다.");
        $project = Project::find($id);
        if(!$project) return redirect()->route("projects.rewrite", [$id])->with("flash_message", "해당 페이지는 존재하지 않습니다.");

        $input = $req->only(["title", "main_lang", "description", "back_color", "font_color", "hash_tag", "dev_start", "dev_end", "root"]);

        $thumbnail = $req->file("thumbnail");
        $execute_file = $req->file("execute_file");

        $date['dev_start'] = date("Y-m-d", strtotime($input['dev_start']));
        $date['dev_end'] = date("Y-m-d", strtotime($input['dev_end']));

        /* 파일을 제외한 모든 입력값 검사 */
        $rules = [
            "title" => ["required", "max:150"],
            "description" => "required",
            "main_lang" => "required",
            "back_color" => ["required", "regex:/^#[a-fA-F0-9]{0,6}$/"],
            "font_color" => ["required", "regex:/^#[a-fA-F0-9]{0,6}$/"],
            "hash_tag" => "required",
            "root" => ["required", "regex:/^.+\\.(js|php|html)$/"],
            "dev_start" => ["required", "date_format:Y-m-d"],
            "dev_end" => ["required", "date_format:Y-m-d"],
        ];

        $errors = [
            "required" => "내용을 입력해 주세요.",
            "title.max" => "제목은 150자까지 작성할 수 있습니다.",
            "back_color.regex" => "올바른 색상을 입력해 주세요.",
            "font_color.regex" => "올바른 색상을 입력해 주세요.",
            "root.regex" => "올바른 파일 경로를 설정해 주세요.",
            "date_format" => "올바른 날짜 형식을 입력해 주세요.",
        ];

        $validation = Validator::make($input, $rules, $errors);
        if($validation->fails()) return redirect()->route("projects.rewrite", [$id])->withInput()->withErrors($validation);

        /* 섬네일 이미지 검사 */

        if($thumbnail){
            // 이미지 파일 제한
            if(strncmp($thumbnail->getClientMimeType(), "image", 5) !== 0){
                return redirect()->route("projects.rewrite", [$id])->withInput()->withErrors(["thumbnail" => "이미지 파일만 업로드 할 수 있습니다."]);
            }
            // 이미지 용량 제한
            if($thumbnail->getClientSize() > 1024 * 1024 * 2){
                return redirect()->route("projects.rewrite", [$id])->withInput()->withErrors(["thumbnail" => "최대 2MB까지만 업로드 가능합니다."]);
            }
            // 이미지 확장자 제한
            $exts = ["jpg", "png", "jpeg", "gif"];
            if(!in_array(strtolower($thumbnail->getClientOriginalExtension()), $exts)){
                return redirect()->route("projects.rewrite", [$id])->withInput()->withErrors(["thumbnail" => ".jpg, .jpeg, .png, .gif 확장자 파일만 업로드할 수 있습니다."]);
            }
            $input['thumbnail'] = "thumbnail.". $thumbnail->getClientOriginalExtension();

            // 기존 섬네일 삭제
            unlink(public_path("files".DS.$project->saved_folder.DS.$project->thumbnail));
            // 새로운 섬네일 추가
            $thumbnail->move(public_path("files".DS.$project->saved_folder), "thumbnail.".$thumbnail->getClientOriginalExtension());
        }

        /* 실행 파일 검사 */

        if($execute_file){
            // zip 확장자 검사
            if(strtolower($execute_file->getClientOriginalExtension()) !== "zip"){
                return redirect()->route("projects.rewrite", [$id])->withInput()->withErrors(["execute_file" => "zip 형식 압축 파일만 업로드할 수 있습니다."]);
            }
            unlink(public_path("files".DS.$project->saved_folder, "compress.zip"));
            $execute_file->move(public_path("files".DS.$project->saved_folder), "compress.".$execute_file->getClientOriginalExtension());
        }
        $project->update($input);
        return redirect()->route("projects.view", [$id]);
    }

    public function deleteProject($id)
    {
        $project = Project::find($id);
        if(!$project) return redirect()->route("projects.home")->with("flash_message", not_find_message("프로젝트"));
        if(!admin()) return redirect()->route("projects.home")->with("flash_message", AUTH_MESSAGE);
        all_rm(project_path($project->saved_folder));
        $project->delete();
        return location_replace(route("projects.home"));
    }

    public function fileDownload($id)
    {
        $referer = $_SERVER['HTTP_REFERER'];
        $temp = isset($_SERVER['HTTPS']) ? "https" : "http";
        $temp .= "://" . $_SERVER['HTTP_HOST'] . "/projects/view/". $id;

        /* 접근 제어 */
        if(!auth()->user()) return redirect()->route("users.login")->with("flash_message", LOGIN_MESSAGE);
        if($referer !== $temp) return redirect()->route("projects.home")->with("flash_message", AUTH_MESSAGE);
        $project = Project::find($id);
        if(!$project) return redirect()->route("home")->with("flash_message", not_find_message("프로젝트"));
        $file_path = project_path($project->saved_folder).DS."compress.zip";
        if(!is_file($file_path)) return redirect()->route("projects.view", [$id])->with("flash_message", not_find_message("프로젝트의 파일"));

        /* 파일 다운로드 */
        header("Content-Type", "application/octet-stream");
        header("Content-Disposition: attachment; filename=". $project->title .".zip");
        readfile($file_path);
    }
}
