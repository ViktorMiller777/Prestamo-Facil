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
        Schema::create('distribuidoras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->unique()->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->string('estado');
            $table->decimal('linea_credito', 10, 2)->default(0);
            $table->integer('puntos')->default(0);
            $table->decimal('geolocalizacion_lat', 10, 7)->nullable();
            $table->decimal('geolocalizacion_lng', 10, 7)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribuidoras_');
    }
};
