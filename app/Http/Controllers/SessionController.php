<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionController extends Controller
{
    // 로그인 세션 처리
    public function loginPage(){
        return view("users/login");
    }
    public function createSession(Request $request){
        $user_data = $request->only("user_id", "password");

        if (!auth()->attempt($user_data))
            return redirect()->route("users.login")->withErrors(["login_message" => "회원정보와 일치하는 회원이 존재하지 않습니다."]);
        else
            return redirect()->route("home");
    }
    public function destroySession(){
        Auth()->logout();
        return redirect()->route("home");
    }
}
