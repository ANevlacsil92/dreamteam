<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlayDownloadsLinkToPlaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plays', function (Blueprint $table) {
            $table->string('downloads_link')->nullable()->after('author_link');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plays', function (Blueprint $table) {
              $table->dropColumn('downloads_link');
        });
    }
}
