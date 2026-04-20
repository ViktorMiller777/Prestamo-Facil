<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('relaciones', function (Blueprint $table) {
            $table->id();
            $table->string('num_distribuidora'); // "XXXXXXXXX" [cite: 2]
            $table->string('nombre_distribuidora'); // "Luz Maria Rivas Ruiz" [cite: 2]
            $table->string('domicilio'); // Calle las cruces, Villa Jardin [cite: 2]
            $table->decimal('limite_de_credito', 12, 2); // "\$20,000" [cite: 4]
            $table->decimal('credito_disponible', 12, 2); // "\$5,200" [cite: 4]
            $table->integer('puntos')->default(0); // "346" [cite: 5]
            
            $table->string('referencia_de_pago'); // "XXXXXXXXXYYYYYYYYY" [cite: 6]
            $table->date('fecha_limite_pago'); // "16 de febrero 2026" 
            $table->string('pago_anticipado')->nullable(); // "13,14,15 de febrero" 
            $table->decimal('total_pagar', 12, 2); // "\$4,800.00" 

            $table->string('pagos_realizados');

            $table->decimal('recargos', 12, 2)->default(0.00);
            $table->decimal('total', 12, 2)->default(0.00); // Total por fila [cite: 8]
            $table->decimal('totales', 12, 2)->default(0.00); // Sumatoria final [cite: 8]

            $table->string('nombre_empresa')->default('Prestamo Fácil SA'); // [cite: 9]
            $table->string('convenio'); // "1628789" [cite: 12]
            $table->string('clabe'); // Corrigiendo 'clabe' 

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('relaciones');
    }
};
