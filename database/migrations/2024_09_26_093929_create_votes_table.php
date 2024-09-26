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
        Schema::create('votes', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID for each vote
            $table->foreignId('recipe_id')->constrained()->onDelete('no action'); // Foreign key for recipes with NO ACTION on delete
            $table->foreignId('user_id')->constrained()->onDelete('no action'); // Foreign key for users with NO ACTION on delete
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('votes');
    }
};
