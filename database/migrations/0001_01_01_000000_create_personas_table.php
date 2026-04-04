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
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->enum('sexo',['M','F','O']);
            $table->date('fecha_nacimiento');
            $table->string('CURP')->unique();
            $table->string('RFC')->unique();
            $table->integer('telefono_personal')->unique();
            $table->integer('celular')->unique();
            $table->rememberToken();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
    // public function down(): void
    // {
    //     Schema::dropIfExists('jobs');
    //     Schema::dropIfExists('job_batches');
    //     Schema::dropIfExists('failed_jobs');
    // }
};
