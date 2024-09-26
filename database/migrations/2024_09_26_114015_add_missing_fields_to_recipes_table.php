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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('event_location'); // Event Location/Place
            $table->date('event_day'); // Day of the event
            $table->time('event_time')->default('00:00:00'); // Event Time (whole day event, can be handled by time if needed)
            $table->json('participating_chefs'); // Chef's who will participate (stored as JSON to handle multiple chefs)
            $table->json('event_recipes'); // Recipes for the event (stored as JSON to handle multiple recipes)
            $table->decimal('charges', 8, 2)->nullable(); // Charges for the Event (Ticket price)
            $table->string('contact_number'); // Contact Number for inquiries
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
};
