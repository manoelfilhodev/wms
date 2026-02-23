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
        Schema::create('_tb_etiquetas', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('unidade_id')->index('unidade_id');
            $table->integer('material_id')->index('material_id');
            $table->string('codigo_barra', 100)->nullable()->index('idx_etiqueta_codigo');
            $table->string('numero_palete', 50)->nullable();
            $table->timestamp('data_emissao')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_tb_etiquetas');
    }
};
