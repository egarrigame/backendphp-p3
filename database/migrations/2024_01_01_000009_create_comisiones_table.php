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
        Schema::create('comisiones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gestora_id');
            $table->unsignedBigInteger('incidencia_id');
            $table->decimal('monto_base', 8, 2);
            $table->decimal('porcentaje_aplicado', 5, 2);
            $table->decimal('monto_comision', 8, 2);
            $table->date('mes');
            $table->boolean('pagada')->default(false);
            $table->timestamps();

            // Foreign keys
            $table->foreign('gestora_id')->references('id')->on('empresas_gestoras')->onDelete('cascade');
            $table->foreign('incidencia_id')->references('id')->on('incidencias')->onDelete('cascade');

            // Unique composite index
            $table->unique(['gestora_id', 'incidencia_id', 'mes'], 'comisiones_gestora_incidencia_mes_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comisiones');
    }
};
