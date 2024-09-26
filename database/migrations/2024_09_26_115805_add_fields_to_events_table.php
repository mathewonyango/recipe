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
        Schema::table('events', function (Blueprint $table) {
            $table->string('location')->nullable(); // Event Location/Place
            $table->time('time')->nullable();       // Time (whole day)
            $table->decimal('charges', 10, 2)->nullable(); // Charges of the Event
            $table->date('day_of_event')->nullable(); // Day of The event
            $table->string('contact_number', 15)->nullable(); // Contact Number for inquiries
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('location');
            $table->dropColumn('time');
            $table->dropColumn('charges');
            $table->dropColumn('day_of_event');
            $table->dropColumn('contact_number');
        });
    }
};
