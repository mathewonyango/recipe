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
        Schema::table('users', function (Blueprint $table) {
            $table->string('bio', 500)->nullable()->after('certification'); // Bio - optional short description
            $table->enum('payment_status', ['Paid', 'Unpaid'])->default('Unpaid')->after('bio'); // Payment status
            $table->json('social_media_links')->nullable()->after('payment_status'); // Social media links - JSON field
            $table->json('events_participated')->nullable()->after('social_media_links'); // Events participated - JSON field
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
            $table->dropColumn(['bio', 'payment_status', 'social_media_links', 'events_participated']);
        });
    }
};
