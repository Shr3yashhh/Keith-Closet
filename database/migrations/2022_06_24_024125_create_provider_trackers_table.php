<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProviderTrackersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider_trackers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained('users');
            $table->string('current_latitude')->nullable();
            $table->string('current_longitude')->nullable();
            $table->string('last_latitude')->nullable();
            $table->string('last_longitude')->nullable();
            $table->boolean('is_active')->default(0);
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
        Schema::dropIfExists('provider_trackers');
    }
}
