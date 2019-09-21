<?php

namespace App\Http\Controllers;

use App\Log;
use App\Session;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    function __construct(){
        parent::__construct();
    }

    // 회원 정보 처리
    public function joinPage(){
        return view("users/join");
    }
    public function insertUser(Request $request)
    {
        $form_rules = [
            "user_id" => "required|max:255|regex:/^([a-z0-9]+)$/|not_regex:/^([0-9]+)$/",
            "password" => "required|max:255|min:8|confirmed|regex:/^(?=.*[a-zA-Z])(?=.*[0-9])(.{8,})$/",
            "user_email" => "required|max:255|email|regex:/^([a-zA-Z0-9-_]+)@([a-zA-Z0-9-_]+)\.([a-zA-Z0-9-_]{3,4})$/",
            "user_name" => "required|max:255|not_regex:/^([0-9]+)$/"
        ];

        $form_errors = [
            "user_id.required" => "아이디를 입력해 주세요.",
            "password.required" => "비밀번호를 입력해 주세요.",
            "user_email.required" => "이메일을 입력해 주세요.",
            "user_name.required" => "이름을 입력해 주세요.",
            "max" => "최대 255자까지만 입력할 수 있습니다.",
            "user_id.not_regex" => "아이디는 영문 소문자와 숫자로만 작성할 수 있습니다.",
            "user_id.regex" => "아이디는 숫자로만 구성될 수 없습니다.",
            "password.min" => "비밀번호는 최소 8자 이상이여야 합니다.",
            "password.confirmed" => "비밀번호와 비밀번호 확인이 일치하지 않습니다.",
            "password.not_regex" => "비밀번호는 영문/숫자 조합으로 8자 이상 작성해 주십시오.",
            "user_email.email" => "정확한 이메일 주소를 작성해 주시기 바랍니다.",
            "user_email.regex" => "정확한 이메일 주소를 작성해 주시기 바랍니다.",
            "user_name.regex" => "닉네임은 숫자로만 작성 될 수 없습니다."
        ];

        $validator = Validator::make($request->input(), $form_rules, $form_errors);
        if ($validator->fails()) return redirect()->route("users.join")->withErrors($validator)->withInput();

        if(User::where("user_id", $request->post("user_id"))->first())
            return redirect()->route("users.join")->withErrors(["user_id" => "해당 아이디의 유저가 이미 존재합니다."])->withInput();
        if(User::where("user_email", $request->post("user_email"))->first())
            return redirect()->route("users.join")->withErrors(["user_email" => "해당 이메일을 가진 유저가 이미 존재합니다."])->withInput();

        $user_data = [
            "user_id" => $request->post("user_id"),
            "password" => bcrypt($request->post("password")),
            "user_email" => $request->post("user_email"),
            "user_name" => $request->post("user_name"),
            "auth" => 0
        ];

        $user = User::create($user_data);


        /* 로그인 작업 */
        do {
            $key = str_random();
        } while(Session::where("session_key", $key)->first());
        $expire = time() + 3600 * 24;
        session("current_key", $key);
        setcookie("SESSION_KEY", $key, $expire); // 하루동안 로그인 유지
        Session::insert(["uid" => $user->id, "expire" => date("Y-m-d H:i:s", $expire), "session_key", $key]);
        Log::insert(["uid" => $user->id, "ip_address" => $_SERVER['REMOTE_ADDR'], "logged_at" => date("Y-m-d", time())]);
        auth()->login($user);

        return redirect()->route("home");
    }

    // 데이터 조회
    public function issetUserById($id){
        header("Content-Type: application/json");
        $user = User::where("user_id", $id)->get();
        return response()->json(['exist' => count($user) === 0 ? false : true, 'target' => $user]);
    }

    public function issetUserByEmail($email){
        $user = User::where("user_email", $email)->first();
        return response()->json(['exist' => $user ? true : false, 'target' => $user]);
    }
}
