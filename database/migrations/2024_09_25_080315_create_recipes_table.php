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

        Schema::create('recipes', function (Blueprint $table) {
            $table->id();  // Auto-incrementing primary key
            $table->string('title');  // Recipe title
            $table->foreignId('topic_id')->constrained('topics')->onDelete('cascade');  // Foreign key to topics table
            $table->integer('servings');  // Number of servings
            $table->integer('prep_time');  // Preparation time in minutes
            $table->integer('cook_time');  // Cooking time in minutes
            $table->integer('total_time');  // Total time (prep + cook time)
            $table->text('ingredients');  // Ingredients (can be JSON-encoded)
            $table->text('instructions');  // Instructions for the recipe
            $table->timestamps();  // Timestamps for created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
