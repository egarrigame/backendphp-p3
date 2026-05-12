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
            $table->unsignedBigInteger('gestora_id')->nullable()->after('zona_id');
            $table->string('nombre_residente', 100)->nullable()->after('gestora_id');

            $table->foreign('gestora_id')->references('id')->on('empresas_gestoras');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incidencias', function (Blueprint $table) {
            $table->dropForeign(['gestora_id']);
            $table->dropColumn(['gestora_id', 'nombre_residente']);
        });
    }
};
