<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestedServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requested_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('provider_id')->constrained('users');
            $table->string('user_latitude')->nullable();
            $table->string('user_longitude')->nullable();
            $table->enum('status',['pending','confirmed','rejected','cancelled'])->default('pending');
            $table->boolean('is_canceled')->default(0);
            $table->boolean('is_seen')->default(1);
            $table->boolean('is_seen_admin')->default(0);
            $table->integer('rating')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requested_services');
    }
}
