<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBinLocatinoInProductWarehouse extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product_warehouses', function (Blueprint $table) {
            $table->string('bin_location')->nullable()->after('quantity'); // Change 'quantity' to the appropriate column after which you want to place this
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_warehouses', function (Blueprint $table) {
            $table->dropColumn('bin_location');
        });
    }
}
