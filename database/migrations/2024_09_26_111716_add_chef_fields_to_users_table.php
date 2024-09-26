<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // First, check if the username column exists before attempting to modify it
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->unique()->nullable()->default(null); // Add the username column
            } else {
                $table->string('username')->unique()->nullable()->default(null)->change(); // Modify the existing username column
            }

            // Adding other columns
            $table->text('profile_picture')->nullable()->after('password');
            $table->enum('experience_level', ['Beginner', 'Intermediate', 'Professional'])->default('Beginner')->after('profile_picture');
            $table->string('cuisine_type')->nullable()->after('experience_level');
            $table->string('location')->default('Unknown')->after('cuisine_type');
            $table->text('certification')->nullable()->after('location');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the added columns
            $table->dropColumn(['username', 'profile_picture', 'experience_level', 'cuisine_type', 'location', 'certification']);
        });
    }

};
