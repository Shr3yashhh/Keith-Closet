<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\DataImport\Enums\ImportDataStatusEnums;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("import_data", function (Blueprint $table) {
            $table->id("id");
            $table->foreignId('import_id')->constrained('import')->onDelete('cascade');
            $table->json("data");
            $table->string("status", 25)
                ->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("import_data");
    }
};
