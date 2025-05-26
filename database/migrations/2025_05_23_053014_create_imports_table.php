<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\DataImport\Enums\ImportStatusEnums;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("import", function (Blueprint $table) {
            $table->id("id");
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string("type", 30)->index();
            $table->uuid("batch_id")->nullable();
            $table->bigInteger("total_rows")->nullable();
            $table->bigInteger("updated_rows")->nullable();
            $table->json("data");
            $table->string("status", 25)->index();
            $table->timestamps();
            // $table->foreign("created_by")
            //     ->references("id")
            //     ->on("users")
            //     ->onDelete("set null");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("imports");
    }
};
