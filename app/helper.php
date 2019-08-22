<?php

/**
 * DS :: Directory Separator
 */
define("DS", DIRECTORY_SEPARATOR);

/*
* admin :: 현재 접속한 유저가 관리자인지 확인한다
*
* @returned :: 관리자라면 해당 유저의 정보를, 아니라면 false를 반환한다.
*/

if(!function_exists("admin")){
    function admin(){
        return auth()->user() && auth()->user()->auth ? auth()->user() : false;
    }
}


/*
c_mkdir(Custom Make Directory) :: 랜덤으로 public/files 경로에 디렉토리를 생성한다.

@Parameter ::
$parents_dir >> 해당 파라메터의 값이 존재하면, 해당 이름의 디렉토리 안에 랜덤 이름의 디렉토리를 생성한다.

@returned ::
$dir_name >> 생성된 디렉토리의 이름을 리턴한다.
*/

if(!function_exists("c_mk_dir")){
    function c_mkdir($parents_dir = ""){
        if($parents_dir){
            if(!is_dir(public_path("files". DS. $parents_dir ))){
                mkdir(public_path("files".DS.$parents_dir), 0777, true);
            }
            $parents_dir .= DS;
        }

        do {
            $dir_name = str_random();
        } while(is_dir(public_path("files".DS.$parents_dir.$dir_name)));
        return $dir_name;
    }
}

/**
 * project_path($saved_path) :: project 가 저장되어있는 경로를 리턴한다
 *
 * @Parameter ::
 *  $saved_path >> 해당 프로젝트가 저장된 폴더명
 *
 * @Returned ::
 *  $path >> 해당 프로젝트의 경로
 */

if(!function_exists("project_path")){
    function project_path($saved_path){
        return public_path("files".DS."Projects".DS.$saved_path);
    }
}
