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
Route::post("/login", [
    "as" => "session.create",
    "uses" => "SessionController@createSession"
]);
Route::get("/logout", [
    "as" => "session.destroy",
    "uses" => "SessionController@destroySession"
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
Route::get("/users/id/{user_id}", "UserController@issetUserById")
->where("user_id", "[a-zA-Z0-9]+");

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


/**
 * 로그인한 사용자만 접근 가능
 * @middleware => AllowOnlyLogin
 */

Route::group(['middleware' => ['allow.login']], function () {
    /*
    Ajax 요청
    */
    Route::post("/load/ajax", [
        "as" => "ajax.load",
        "uses" => "AjaxController@load"
    ]);


    /*
    즐겨찾기 Bookmark
    */
    Route::get("/bookmarks", [
        "as" => "bookmarks.home",
        "uses" => "BookmarkController@home"
    ]);


    /*
    옵션 Options
    */
    Route::get("/options", [
        "as" => "options.home",
        "uses" => "OptionController@home"
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


    /**
     * 기능대회 준비 Practices
     *
     */

    /* 글 목록 */
    Route::get("/practices/list", [
        "as" => "practices.home",
        "uses" => "PracticeController@home",
    ]);

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
    Route::get("/projects/modify/{id}", [
        "as" => "projects.rewrite",
        "uses" => "ProjectController@modifyPage",
    ])->where(["id" => "[0-9]+"]);
    Route::post("/projects/modify/{id}", [
        "as" => "projects.update",
        "uses" => "ProjectController@updateProject",
    ])->where(["id" => "[0-9]+"]);

    // 글 삭제
    Route::get("/projects/delete/{id}", [
        "as" => "project.delete",
        "uses" => "ProjectController@deleteProject"
    ])->where(["id" => "[0-9]+"]);

    // 파일 다운로드
    Route::get("/projects/download/{id}", [
        "as" => "projects.download",
        "uses" => "ProjectController@fileDownload"
    ]);

    /*
    기능대회 준비 Skills Club
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
});
