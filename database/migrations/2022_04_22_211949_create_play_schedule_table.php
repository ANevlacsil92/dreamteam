<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('play_schedule', function (Blueprint $table) {
            $table->id();
            $table->integer("play_id");
            $table->date("practice_date");
            $table->integer("start_line")->nullable();
            $table->integer("end_line")->nullable();
            $table->string("comment")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('play_schedule');
    }
}
