<?php

use App\Enums\AppointmentStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->longText("description")->nullable();
            $table->unsignedBigInteger("doctor_id")->nullable();
            $table->unsignedBigInteger("patient_id")->nullable();
            $table->foreign("doctor_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");
            $table->foreign("patient_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");
            $table->dateTime("date");
            $table->enum("status", AppointmentStatusEnum::getAllValues())
                ->default(AppointmentStatusEnum::PENDING->appointmentType());
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
        Schema::dropIfExists('appointments');
    }
}
