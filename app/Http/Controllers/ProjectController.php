<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    function __construct(){
        $this->data = ["page_name" => "projects"];
    }

    public function home()
    {
        if(!auth()->user()) return redirect()->route("users.login")->with("flash_message", "로그인 후 이용하실 수 있습니다.");
        $data = $this->data;

        /*

        // 카테고리 중복 제거
        $raw_langs = Project::distinct()->where("dev_start", "like", "$year%")->get("main_lang");
        $data['langs'] = [];
        foreach($raw_langs as $lang){
            $lang = explode("|", $lang['main_lang'])[0];
            if(!in_array($lang, $data['langs'])) {
                $cnt = Project::where("main_lang", "like", "%$lang%")->count();
                array_push($data['langs'], ['name' => $lang, 'count' => $cnt]);
            }
        }
        // ALL 카테고리 개수
        $data['total_langs_cnt'] = 0;
        foreach($data['langs'] as $item){
            $data['total_langs_cnt'] += $item['count'];
        }

        $data['projects'] = Project::where("dev_start", "like", "$year%")->get();

        //  프로젝트의 태그, 주 언어 구분
        foreach($data['projects'] as $idx => $project){
            $data['projects'][$idx]->main_lang = explode("|", $project->main_lang)[0];
            $data['projects'][$idx]->hash_tag = array_slice(explode("|", $project->hash_tag), 0, 4);
        }

        */
        return view("projects.home", $data);
    }

    public function writePage()
    {
        $data = $this->data;
        if(!admin()) return redirect()->route("projects.home")->with("flash_message", "권한이 없습니다.");
        return view("projects.write", $data);
    }

    public function insertProject(Request $req)
    {
        if(!admin()) return redirect()->route("projects.home")->with("flash_message", "권한이 없습니다.");
        $data = $req->only("title", "main_lang", "description", "back_color", "font_color", "hash_tag", "root", "dev_start", "dev_end");
        $thumbnail = $req->file("thumbnail");
        $execute_file = $req->file("execute_file");

        // dd($data);

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

        $validator = Validator::make($data, $rules, $errors);
        if($validator->fails())
            return redirect()->route("projects.write")->withInput()->withErrors($validator)->withInput();


        if(count(Project::where("title", $data['title'])->get()) !== 0){
            return redirect()->route("projects.write")->withInput()->withErrors(["title" => "해당 이름의 프로젝트가 이미 존재합니다."]);
        }

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

        $data['thumbnail'] = "thumbnail.". $thumbnail->getClientOriginalExtension();
        $data['saved_folder'] = $dir_name = c_mkdir("Projects");
        $thumbnail->move(project_path($dir_name), "thumbnail.".$thumbnail->getClientOriginalExtension());
        $execute_file->move(project_path($dir_name), "compress.".$execute_file->getClientOriginalExtension());

        Project::create($data);
        return redirect()->route("projects.home");
    }
    public function viewPage($id)
    {
        if(!auth()->user()) return redirect()->route("users.login")->with("flash_message", "로그인 후 이용할 수 있습니다.");
        $data = $this->data;
        $data['project'] = Project::find($id);
        if(!$data['project']) return redirect()->route("projects.home")->with("flash_message", "해당 글은 존재하지 않습니다.");
        $data['project']->main_lang = explode("|", $data['project']->main_lang);
        $data['project']->hash_tag = explode("|", $data['project']->hash_tag);
        return view("projects.view", $data);
    }


    public function modifyPage($id)
    {
        if(!admin()) return redirect()->route("projects.home")->with("flash_message", "권한이 없습니다.");

        $data = $this->data;
        $data['project'] = Project::find($id);
        if(!$data['project']) return redirect()->route("projects.home")->with("flash_message", "해당 글은 존재하지 않습니다.");
        $data['project']->main_lang = explode("|", $data['project']->main_lang);
        $data['project']->hash_tag = explode("|", $data['project']->hash_tag);

        return view("projects.modify", $data);
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
        if(!$project) return redirect()->route("projects.home")->with("flash_message", "해당 프로젝트는 존재하지 않습니다.");
        if(!admin()) return redirect()->route("projects.home")->with("flash_message", "권한이 없습니다.");
        $project->delete();
        return redirect()->route("projects.home")->with("flash_message", "프로젝트가 삭제되었습니다.");
    }

    public function fileDownload($id)
    {
        $referer = $_SERVER['HTTP_REFERER'];
        $temp = isset($_SERVER['HTTPS']) ? "https" : "http";
        $temp .= "://" . $_SERVER['HTTP_HOST'] . "/projects/view/". $id;

        /* 접근 제어 */
        if(!auth()->user()) return redirect()->route("users.login")->with("flash_message", "로그인 후 이용할 수 있습니다.");
        if($referer !== $temp) return redirect()->route("home")->with("flash_message", "허용된 접근이 아닙니다.");
        $project = Project::find($id);
        if(!$project) return redirect()->route("home")->with("flash_message", "해당 프로젝트는 존재하지 않습니다.");
        $file_path = public_path("files".DS.$project->saved_folder.DS."compress.zip");
        // dd($file_path);
        if(!is_file($file_path)) return redirect()->route("projects.view", [$id])->with("flash_message", "해당 프로젝트의 파일이 존재하지 않습니다.");

        /* 파일 다운로드 */
        header("Content-Type", "application/octet-stream");
        header("Content-Disposition: attachment; filename=". $project->title .".zip");
        readfile($file_path);
    }
}
