<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsActiveToExtendedUserPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('extended_user_properties', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('photo_url');
            $table->boolean('show')->default(true)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('extended_user_properties', function (Blueprint $table) {
            $table->dropColumn('is_active');
            $table->dropColumn('show');
        });
    }
}
