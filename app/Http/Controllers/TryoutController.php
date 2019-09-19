<?php

namespace App\Http\Controllers;

use App\Project;
use App\Tryout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TryoutController extends Controller
{
    function __construct(){
        parent::__construct();
        $this->data['page_name'] = "tryouts";
    }

    /**
     * 글 리스트
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home()
    {
        $data = $this->data;

        /* 조건절 설정 */
        $data['condition'] = $condition = (object)[
            "keyword" => isset($_GET['keyword']) && mb_strlen(trim($_GET['keyword'])) > 0 ? "%".$_GET['keyword']."%" : "%_%"
        ];
        $filter = [
            ["title", "LIKE", $condition->keyword]
        ];


        /* 정렬 설정 */
        $order = isset($_GET['order']) && mb_strrpos("-", $_GET['order']) !== -1 ? explode("-", trim($_GET['order'])) : ["w_date", "ASC"];
        $order = count($order) !== 2 ? ['w_date', "ASC"] : $order;
        $order[0] = in_array($order[0], ["w_date", "v_count"]) ? $order[0] : "w_date";
        $order[1] = in_array($order[1], ["ASC", "DESC"]) ? $order[1] : "ASC";

        $data['order'] = $order = (object)[
            "key" => $order[0],
            "direction" => $order[1]
        ];

        /* 페이지네이션 */
        define("APP", 10);  // Article Per Page
        define("PPB", 5); // Page Per Block

        $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] >= 1 ? $_GET['page'] / 1 : 1;
        $totalCount = Tryout::where($filter)->count();
        $totalPage = (int)ceil($totalCount / APP);
        $now_block = (int)ceil($page / PPB);
        $start = ($now_block - 1) * PPB + 1;
        $end = $start + PPB - 1 > $totalPage ? $totalPage : $start + PPB - 1;

        $data['pagination'] = (object)[ "start" => $start, "end" => $end, "page" => $page, "total" => $totalPage ];

        $data['tryouts'] = Tryout::where($filter)->orderBy($order->key, $order->direction)->offset(($page - 1) * APP)->limit(APP)->get();
        return view("tryouts.home", $data);
    }

    /**
     * 글 보기
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewPage($id)
    {
        $data = $this->data;
        $data['tryout'] = $tryout = Tryout::find($id);
        if(!$tryout) return redirect()->route("tryouts.home")->with("flash_message", not_find_message("일지"));
        $tryout->update(["v_count" => (int)$tryout->v_count + 1]);
        return view("tryouts.view", $data);
    }

    /**
     * 글 쓰기
     */
    public function writePage()
    {
        $data = $this->data;
        return view("tryouts.write", $data);
    }
    public function insertTryout(Request $req)
    {
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

    /**
     * 글 수정
     */
    public function rewritePage($id)
    {
        $data = $this->data;
        $data['tryout'] = Tryout::find($id);
        if(!$data['tryout']) return redirect()->route("tryouts.home")->with("flash_message", not_find_message("일지"));
        return view("tryouts.rewrite", $data);
    }
    public function updateTryout(Request $req, $id)
    {
        $origin = Tryout::find($id);
        if(!$origin) return redirect()->route("tryouts.home")->with("flash_message", not_find_message("일지"));

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

        $origin->update($input);
        return redirect()->route("tryouts.view", $id);
    }

    /**
     * 글 삭제
     */
    public function deleteTryout($id)
    {
        $data = Tryout::find($id);
        if(!$data) return redirect()->route("tryouts.home")->with("flash_message", not_find_message("일지"));
        $data->delete();
        return redirect()->route("tryouts.home")->with("flash_message", "일지가 삭제 되었습니다.");
    }
}
