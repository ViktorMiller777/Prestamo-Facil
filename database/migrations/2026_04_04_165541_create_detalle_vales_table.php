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
        Schema::create('detalle_vales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('relacion_id')->nullable()->constrained('relaciones')->onDelete('set null');
            $table->foreignId('vale_id')->constrained('vales')->onDelete('cascade');
            $table->decimal('monto', 10, 2);
            $table->decimal('porcentaje_comision', 5, 2);
            $table->decimal('monto_comision_calculada', 10, 2);
            $table->decimal('interes_quincenal', 5, 2);
            $table->integer('quincenas');
            $table->decimal('seguro', 10, 2)->default(0);
            $table->decimal('comision',10, 2)->default(0);
            $table->decimal('pago',10, 2)->default(0);
            $table->string('nombre_cliente');
            $table->string('nombre_distribuidora');
            $table->timestamp('fecha_emision')->useCurrent();
            $table->string('producto_folio');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_vales');
    }
};
