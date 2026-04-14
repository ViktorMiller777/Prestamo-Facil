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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();            
            $table->decimal('monto', 10, 2);            
            $table->decimal('porcentaje_comision',8,2)->default("0");            
            $table->decimal('seguro', 10, 2)->default(0);            
            $table->integer('quincenas');            
            $table->decimal('interes_quincenal',8,2)->default("0");            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos_');
    }
};
