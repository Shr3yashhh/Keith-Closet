<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beds', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("bed_number");
            $table->unsignedBigInteger("patient_id")->nullable();
            $table->longText("comment")->nullable();
            $table->foreign("patient_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");
            $table->unsignedBigInteger("doctor_id")->nullable();
            $table->foreign("doctor_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");
            $table->softDeletes();
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
        Schema::dropIfExists('beds');
    }
}
