<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePracticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('practices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("title", 150); /* 기능대회 문제명 */
            $table->unsignedBigInteger("created_no"); /* 만들어진 번호 */
            $table->string("saved_folder", 255); /* 저장된 폴더 명 */
            $table->string("thumbnail", 50); /* 섬네일 파일명(확장자 포함) */
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
        Schema::dropIfExists('practices');
    }
}
