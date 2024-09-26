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
    Schema::table('events', function (Blueprint $table) {
        $table->unsignedBigInteger('topic_id')->nullable(); // Adjust according to your needs
        $table->foreign('topic_id')->references('id')->on('topics')->onDelete('NO ACTION'); // Changed to NO ACTION
    });
}


    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['topic_id']);
            $table->dropColumn('topic_id');
        });
    }

};
