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
            // Adding new columns to the existing topics table
            $table->date('start_date')->after('description'); // Start date of the topic
            $table->date('end_date')->after('start_date'); // End date of the topic
            $table->enum('status', ['open', 'closed'])->after('end_date'); // Status of the topic
        });
    }

    public function down()
    {
        Schema::table('topics', function (Blueprint $table) {
            // Dropping the newly added columns in case of rollback
            $table->dropColumn(['start_date', 'end_date', 'status']);
        });
    }

};
