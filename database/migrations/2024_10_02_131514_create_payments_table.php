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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Foreign key to users table
            $table->decimal('amount', 8, 2); // Amount paid
            $table->enum('status', ['paid', 'unpaid', 'partial'])->default('unpaid'); // Payment status
            $table->string('transaction_id')->nullable(); // Optional transaction ID
            $table->timestamps();

            // Foreign key constraint to relate to users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
