<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn(['comprobante_domicilio', 'INE']);
        });

        Schema::table('distribuidoras', function (Blueprint $table) {
            $table->dropColumn(['comprobante_domicilio', 'ine']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('comprobante_domicilio')->nullable();
            $table->string('INE')->nullable();
        });

        Schema::table('distribuidoras', function (Blueprint $table) {
            $table->string('comprobante_domicilio')->nullable();
            $table->string('ine')->nullable();
        });
    }
};
