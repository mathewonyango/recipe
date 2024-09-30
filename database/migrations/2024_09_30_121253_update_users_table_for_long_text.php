<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Changing profile_picture and certification columns to LONGTEXT
            $table->longText('profile_picture')->nullable()->change();
            $table->longText('certification')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Reverting the changes (assuming they were initially TEXT type)
            $table->text('profile_picture')->nullable()->change();
            $table->text('certification')->nullable()->change();
        });
    }

};
