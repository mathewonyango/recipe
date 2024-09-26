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
            $table->string('username')->unique()->nullable()->default(null)->change(); // Change default to NULL and make it nullable
            $table->text('profile_picture')->nullable()->after('password');
            $table->enum('experience_level', ['Beginner', 'Intermediate', 'Professional'])->default('Beginner')->after('profile_picture');
            $table->string('cuisine_type')->nullable()->after('experience_level');
            $table->string('location')->default('Unknown')->after('cuisine_type');
            $table->text('certification')->nullable()->after('location');
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
            $table->dropColumn(['username', 'profile_picture', 'experience_level', 'cuisine_type', 'location', 'certification']);
        });
    }
};
