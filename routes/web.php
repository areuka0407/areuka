<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;



Route::get("/", [
    "as" => "home",
    "uses" => "CommonController@index"
]);

/*
 세션 Session
*/
Route::get("/login", [
    "as" => "users.login",
    "uses" => "SessionController@loginPage"
]);
Route::get("/login/{user_id}", [
    "as" => "users.select_login",
    "uses" => "SessionController@selectLoginPage"
])->where("user_id", "[0-9a-z]+");
Route::post("/login", [
    "as" => "session.create",
    "uses" => "SessionController@createSession"
]);


/*
 유저 User
*/
Route::get("/join", [
    "as" => "users.join",
    "uses" => "UserController@joinPage"
]);
Route::post("/join", [
    "as" => "users.create",
    "uses" => "UserController@insertUser"
]);

/*
회원정보 찾기 Remind
*/
Route::get("/remind/id", [
    "as" => "remind.id",
    "uses" => "remindController@remindId"
]);
Route::get("/remind/password", [
    "as" => "remind.password",
    "uses" => "remindController@remindPassword"
]);

/*
    Ajax 요청
    */
Route::post("/load/{table}", [
    "as" => "ajax.load",
    "uses" => "AjaxController@load"
]);


/**
 * 로그인한 사용자만 접근 가능
 * @middleware => AllowOnlyLogin
 */

Route::group(['middleware' => ['allow.login']], function () {
    /*
     * 로그아웃
     */
    Route::get("/logout", [
        "as" => "session.destroy",
        "uses" => "SessionController@destroySession"
    ]);

    /**
     * Projects 프로젝트
     */

    // 글 목록
    Route::get("/projects/{year?}", [
        "as" => "projects.home",
        "uses" => "ProjectController@home"
    ])->where(["year" => "^[0-9]+$"]);

    // 글 보기
    Route::get("/projects/view/{id}", [
        "as" => "projects.view",
        "uses" => "ProjectController@viewPage"
    ])->where(["id" => "[0-9]+"]);


    /*
    삽질 일기 Try Out
    */

    /* 글 목록 */
    Route::get("/tryouts", [
        "as" => "tryouts.home",
        "uses" => "TryoutController@home",
    ]);
    // 글 보기
    Route::get("/tryouts/view/{id}", [
        "as" => "tryouts.view",
        "uses" => "TryoutController@viewPage"
    ])->where("id", "[0-9]+");

    /**
     * 기능대회 준비 Practices
     *
     */

    /* 글 목록 */
    Route::get("/practices/{year?}", [
        "as" => "practices.home",
        "uses" => "PracticeController@home",
    ])->where(["year" => "[0-9]{4}"]);

    // 글 보기
    Route::get("/practices/view/{id}", [
        "as" => "practices.view",
        "uses" => "PracticeController@viewPage"
    ])->where(["id" => "[0-9]+"]);
});


/**
 * 관리자만 접근 가능
 * @middleware => "AllowOnlyAuth"
 */
Route::group(['middleware' => ['allow.auth']], function () {

    /**
     * 프로젝트 Projects
     */

    // 글 쓰기
    Route::get("/projects/write", [
        "as" => "projects.write",
        "uses" => "ProjectController@writePage"
    ]);
    Route::post("/projects/write", [
        "as" => "projects.insert",
        "uses" => "ProjectController@insertProject"
    ]);

    // 글 수정
    Route::get("/projects/rewrite/{id}", [
        "as" => "projects.rewrite",
        "uses" => "ProjectController@rewritePage",
    ])->where(["id" => "[0-9]+"]);
    Route::post("/projects/rewrite/{id}", [
        "as" => "projects.update",
        "uses" => "ProjectController@updateProject",
    ])->where(["id" => "[0-9]+"]);

    // 글 삭제
    Route::get("/projects/delete/{id}", [
        "as" => "projects.delete",
        "uses" => "ProjectController@deleteProject"
    ])->where(["id" => "[0-9]+"]);

    // 파일 다운로드
    Route::get("/projects/download/{id}", [
        "as" => "projects.download",
        "uses" => "ProjectController@fileDownload"
    ]);

    /*
    기능대회 준비 Practice
    */

    /* 글 쓰기 */
    Route::get("/practices/write", [
        "as" => "practices.write",
        "uses" => "PracticeController@writePage"
    ]);
    Route::post("/practices/write", [
        "as" => "practices.insert",
        "uses" => "PracticeController@insertPractice"
    ]);

    /* 글 수정 */
    Route::get("/practices/rewrite/{id}", [
        "as" => "practices.rewrite",
        "uses" => "PracticeController@rewritePage"
    ])->where(["id" => "[0-9]+"]);

    Route::post("/practices/rewrite/{id}", [
        "as" => "practices.update",
        "uses" => "PracticeController@updatePractice"
    ])->where(["id" => "[0-9]+"]);

    /* 글 삭제 */
    Route::get("/practices/delete/{id}", [
        "as" => "practices.delete",
        "uses" => "PracticeController@deletePractice"
    ])->where(["id" => "[0-9]+"]);

    /* 파일 다운로드 */
    Route::get("/practices/download/{id}", [
        "as" => "practices.download",
        "uses" => "PracticeController@fileDownload"
    ])->where("id", "[0-9]+");

    /**
     * 삽질 일기 Tryout
     */

    // 글 쓰기
    Route::get("/tryouts/write", [
        "as" => "tryouts.write",
        "uses" => "TryoutController@writePage"
    ]);
    Route::post("/tryouts/write", [
        "as" => "tryouts.insert",
        "uses" => "TryoutController@insertTryout"
    ]);

    // 글 수정
    Route::get("/tryouts/rewrite/{id}", [
        "as" => "tryouts.rewrite",
        "uses" => "TryoutController@rewritePage",
    ])->where("id", "[0-9]+");
    Route::post("/tryouts/rewrite/{id}", [
        "as" => "tryouts.update",
        "uses" => "TryoutController@updateTryout"
    ])->where("id", "[0-9]+");

    // 글 삭제
    Route::get("/tryouts/delete/{id}", [
        "as" => "tryouts.delete",
        "uses" => "TryoutController@deleteTryout"
    ]);
});
