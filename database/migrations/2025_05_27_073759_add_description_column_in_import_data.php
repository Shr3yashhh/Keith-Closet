<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table("import_data", function (Blueprint $table) {
            $table->longText("description")
            ->after("data")
            ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table("import_data", function (Blueprint $table) {
            $table->dropColumn(["description"]);
        });
    }
};
