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
        Schema::create('incidencias', function (Blueprint $table) {
            $table->id();
            $table->string('localizador', 12)->unique();
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('tecnico_id')->nullable();
            $table->unsignedBigInteger('especialidad_id');
            $table->unsignedBigInteger('estado_id')->default(1);
            $table->unsignedBigInteger('zona_id')->nullable();
            $table->text('descripcion');
            $table->string('direccion', 255);
            $table->dateTime('fecha_servicio');
            $table->enum('tipo_urgencia', ['estandar', 'urgente'])->default('estandar');
            $table->decimal('precio_base', 8, 2)->default(0);
            $table->timestamps();

            // Foreign keys
            $table->foreign('cliente_id')->references('id')->on('usuarios')->onDelete('restrict');
            $table->foreign('tecnico_id')->references('id')->on('tecnicos')->onDelete('no action');
            $table->foreign('especialidad_id')->references('id')->on('especialidades')->onDelete('restrict');
            $table->foreign('estado_id')->references('id')->on('estados')->onDelete('restrict');
            $table->foreign('zona_id')->references('id')->on('zonas')->onDelete('set null');

            // Named indexes
            $table->index('cliente_id', 'idx_cliente');
            $table->index('tecnico_id', 'idx_tecnico');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidencias');
    }
};
