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
        Schema::create('topics', function (Blueprint $table) {
            $table->id();  // Auto-incrementing primary key
            $table->string('name');  // Topic name (e.g., "Best Vegan Dish")
            $table->text('description')->nullable();  // Description of the topic
            $table->timestamps();  // Timestamps for created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('topics');
    }
};
