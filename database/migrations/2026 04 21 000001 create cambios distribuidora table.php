<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cambios_distribuidora', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cliente_id')
                  ->constrained('clientes')
                  ->cascadeOnDelete();

            $table->foreignId('distribuidora_origen_id')
                  ->constrained('distribuidoras')
                  ->cascadeOnDelete();

            $table->foreignId('distribuidora_destino_id')
                  ->constrained('distribuidoras')
                  ->cascadeOnDelete();

            $table->foreignId('coordinador_id')
                  ->nullable()
                  ->constrained('usuarios')
                  ->nullOnDelete();

            // ── Token A (origen → coordinador) ──────────────────────────
            $table->string('token_origen', 10);
            $table->boolean('token_origen_usado')->default(false);
            $table->dateTime('token_origen_expira_at');

            // ── Token B (coordinador → destino) ─────────────────────────
            $table->string('token_destino', 10)->nullable();
            $table->boolean('token_destino_usado')->default(false);
            $table->dateTime('token_destino_expira_at')->nullable();

            // ── Estado ──────────────────────────────────────────────────
            $table->enum('estado', [
                'pendiente',
                'coordinador_validado',
                'completado',
                'cancelado',
            ])->default('pendiente');

            $table->text('motivo_cancelacion')->nullable();

            // ── Auditoría ───────────────────────────────────────────────
            $table->timestamp('fecha_solicitud')->useCurrent();
            $table->timestamp('fecha_validacion_coord')->nullable();
            $table->timestamp('fecha_completado')->nullable();

            $table->timestamps();

            // Un cliente solo puede tener UN cambio activo a la vez
            // (pendiente o coordinador_validado)
            $table->index(['cliente_id', 'estado'], 'idx_cliente_estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cambios_distribuidora');
    }
};