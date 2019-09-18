<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTryoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tryouts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char("title", 50);               // 제목
            $table->text("contents");                       // 내용 HTML
            $table->integer("v_count")->default(0);   // 조회수
            $table->timestamp("w_date");                    // 작성일
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
        Schema::dropIfExists('tryouts');
    }
}
