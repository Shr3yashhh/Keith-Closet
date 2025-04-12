<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("code");
            $table->unsignedBigInteger("patient_id")->nullable();
            $table->foreign("patient_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");
            $table->unsignedBigInteger("doctor_id")->nullable();
            $table->foreign("doctor_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");
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
        Schema::dropIfExists('medicines');
    }
}
