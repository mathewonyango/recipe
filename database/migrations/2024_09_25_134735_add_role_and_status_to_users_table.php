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
        Schema::table('users', function (Blueprint $table) {
            // Add the role column
            $table->string('role')->default('user'); // Set default role as 'user'

            // Add the status column
            $table->enum('status', ['active', 'inactive'])->default('active'); // Set default status as 'active'
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
            // Drop the role and status columns
            $table->dropColumn('role');
            $table->dropColumn('status');
        });
    }
};
