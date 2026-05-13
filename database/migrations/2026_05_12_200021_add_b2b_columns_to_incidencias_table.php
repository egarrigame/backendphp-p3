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
        Schema::table('incidencias', function (Blueprint $table) {
            $table->foreignId('comunidad_id')->nullable()->constrained('comunidades')->onDelete('set null'); // fk a comunidades
            
            $table->decimal('precio_base', 8, 2)->nullable();
            $table->decimal('porcentaje_comision', 5, 2)->nullable();
            $table->decimal('comision_calculada', 8, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incidencias', function (Blueprint $table) {
            $table->dropForeign(['comunidad_id']); // rollback por si a caso
            $table->dropColumn(['comunidad_id', 'precio_base', 'porcentaje_comision', 'comision_calculada']);
        });
    }
};
