<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('no action'); // User who rated
            $table->foreignId('recipe_id')->constrained()->onDelete('no action'); // Recipe rated
            $table->integer('rating'); // Rating value (e.g. 1-5 stars)
            $table->timestamps(); // Track when the rating happened
        });
    }

    public function down()
    {
        Schema::dropIfExists('ratings');
    }
}
