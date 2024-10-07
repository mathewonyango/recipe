<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewsTable extends Migration
{
    public function up()
    {
        Schema::create('views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('no action'); // No action on delete
            $table->foreignId('recipe_id')->constrained()->onDelete('no action'); // No action on delete
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('views');
    }
}
