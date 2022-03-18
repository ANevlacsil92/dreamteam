<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayTextbooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('play_textbooks', function (Blueprint $table) {
            $table->id();
            $table->integer("play_id");
            $table->integer("play_scenes_id");
            $table->integer("linenumber");
            $table->string("said_by");
            $table->text("text");
            $table->text("following_stage_direction")->nullable();
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
        Schema::dropIfExists('play_textbooks');
    }
}
