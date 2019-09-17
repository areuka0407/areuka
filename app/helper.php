<?php

define("DS", DIRECTORY_SEPARATOR);
define("LOGIN_MESSAGE", "로그인 후 이용할 수 있습니다.");
define("AUTH_MESSAGE", "권한이 없습니다.");
define("NOT_FIND_MESSAGE", "해당 $1를 찾을 수 없습니다.");

/**
 * not_find_message($name) :: 매개변수로 입력받은 이름을 찾을 수 없다는 메시지를 반환한다.
 *
 * @Parameter ::
 * $name > 메세지에 입력할 이름을 넣는다
 *
 * @Returned ::
 * $message > 해당 $name을 찾을 수 없다는 메세지를 반환한다.
 */

if(!function_exists("not_find_message")){
    function not_find_message($name){
        return preg_replace("/^\$1$/", $name, NOT_FIND_MESSAGE);
    }
}


/**
 * user() :: 현재 접속한 유저가 로그인했는지 확인한다
 *
 * @Returned ::
 * > 로그인 했다면 해당 유저의 정보를, 아니라면 false를 반환한다.
 */

 if(!function_exists("user")){
     function user(){
         return auth()->check() ? auth()->user() : false;
     }
 }

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


/**
* c_mkdir(Custom Make Directory) :: 랜덤으로 public/files 경로에 디렉토리를 생성한다.
*
* @Parameter ::
* $parents_dir > 해당 파라메터의 값이 존재하면, 해당 이름의 디렉토리 안에 랜덤 이름의 디렉토리를 생성한다.
*
* @Returned ::
* $dir_name > 생성된 디렉토리의 이름을 리턴한다.
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
        mkdir(public_path("files".DS.$parents_dir.$dir_name));
        return $dir_name;
    }
}

/**
 * all_rm($path) :: 하위 경로에 존재하는 모든 파일 및 디렉토리를 삭제하고 해당 디렉토리를 지운다
 *
 * @Parameter ::
 * $path > 지울 디렉토리 경로
 */

if(!function_exists("all_rm")){
    function all_rm($path){
        if(is_file($path)) unlink($path);
        else {
            $innerDir = scandir($path);
            foreach($innerDir as $item){
                if($item === "." || $item === "..") continue;
                all_rm($path.DS.$item);
            }
            rmdir($path);
        }
    }
}


/**
 * project_path($saved_path) :: project 가 저장되어있는 경로를 리턴한다
 *
 * @Parameter ::
 *  $saved_path > 해당 프로젝트가 저장된 폴더명
 *
 * @Returned ::
 *  $path > 해당 프로젝트의 경로
 */

if(!function_exists("project_path")){
    function project_path($saved_path){
        return public_path("files".DS."Projects".DS.$saved_path);
    }
}

/**
 * practice_path($saved_path, $c_no) :: practice 가 저장된 경로를 반환한다.
 *
 * @Parameter ::
 * $saved_path > 해당 연습 파일이 저장된 폴더 명
 * $c_no > 해당 연습 파일이 몇번째 연습 파일인지의 순번
 *
 * @Returned ::
 * $path > 해당 연습파일의 경로
 */

if(!function_exists("practice_path")){
    function practice_path($saved_path, $c_no = null){
        return $c_no === null ? public_path("files".DS."Practices".DS.$saved_path) :  public_path("files".DS."Practices".DS.$saved_path.DS.$c_no);
    }
}

/**
 * Location_replace($url) :: location.replace()을 사용해 경로를 교체한다.
 *
 * @Parameter ::
 * $url > 교체할 경로
 *
 */

if(!function_exists("location_replace")){
    function location_replace($url){
        echo "<script type='text/javascript'>location.replace('$url');</script>";
    }
}