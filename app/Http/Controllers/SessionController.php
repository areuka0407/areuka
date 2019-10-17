<?php

namespace App\Http\Controllers;

use App\Log;
use App\Session;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SessionController extends Controller
{
    // 로그인 세션 처리
    public function loginPage(){
        return view("users.login");
    }
    public function selectLoginPage($user_id){
        $data['user'] = User::where("user_id", $user_id)->first();
        return view("users.select_login", $data);
    }
    public function createSession(Request $request){
        $input = $request->only("user_id", "password");
        $referer = $request->input("select") ? route("users.select_login", $input['user_id']) : route("users.login");
        $maintain = $request->input("maintain");
        $current_time = time();

        /* 유효성 검사 */
        $user = User::where("user_id", $input['user_id'])->first();
        if(!$user) return redirect()->route("users.login")->withErrors(["login_message" => "회원정보와 일치하는 회원이 존재하지 않습니다."]);
        if(!Hash::check($input['password'], $user->password)) return redirect($referer)->withErrors(["login_message" => "비밀번호가 일치하지 않습니다."]);

        do { // 중복되지 않도록 키 생성
            $key = str_random();
        } while( Session::where("session_key", $key)->count() !== 0 );
        $expire = $maintain !== null ? $current_time + 3600 * 24 * 30 : $current_time + 3600 * 24;

        // 로그인 기록 추가
        $log = Log::where("ip_address", $_SERVER['REMOTE_ADDR'])->first();
        if($log) $log->update(["uid" => $user->id, "logged_at" => date("Y-m-d", time())]);
        else Log::insert(["ip_address" => $_SERVER['REMOTE_ADDR'], "uid" => $user->id, "logged_at" => date("Y-m-d", time())]);

        /* 세션 추가 */
        setcookie("SESSION_KEY", $key, $expire, "/");
        $session = Session::where("uid", $user->id)->first();
        if ($session) {
            DB::update("UPDATE sessions SET session_key = ?, expire = ? WHERE id = ?", [$key, date("Y-m-d H:i:s", $expire), $session->id]);
        }
        else Session::insert(["uid" => $user->id, "expire" => date("Y-m-d H:i:s", $expire), "session_key" => $key]);
        session(["current_key" => $key]);
        auth()->attempt($input);
        return redirect()->route("home");
    }
    public function destroySession(){
        Auth()->logout();
        Session::where("session_key", $_COOKIE['SESSION_KEY'])->first()->delete();
        setcookie("SESSION_KEY", "", 0);
        session()->forget("current_key");
        return redirect()->route("home");
    }
}
