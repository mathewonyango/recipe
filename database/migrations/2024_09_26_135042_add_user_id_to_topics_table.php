<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id'); // Adjust as needed
            // Optionally, add a foreign key constraint if user_id references another table
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->dropColumn('user_id');
            // Optionally drop the foreign key if it was added
            // $table->dropForeign(['user_id']);
        });
    }

};
