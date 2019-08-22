<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id'); /* AI */
            $table->string("title", 150); /* 프로젝트 명 */
            $table->string("hash_tag", 255); /* 해시 태그 (배열) */
            $table->text("description"); /* 프로젝트 설명 */
            $table->string("main_lang", 255); /* 주요 사용 언어 (배열) */
            $table->string("saved_folder", 255); /* 저장된 폴더 명 */
            $table->string("thumbnail", 50); /* 섬네일 파일명(확장자 포함) */
            $table->string("back_color", 30)->default("#8BBC4A"); /* 주조색 */
            $table->string("font_color", 30)->default("#3A3A3A"); /* 보조색 */
            $table->date("dev_start"); /* 개발 시작일 */
            $table->date("dev_end"); /* 개발 종료일 */
            $table->string("root", 50)->default("/"); /* 해당 프로젝트의 index 위치 */
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
