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
        Schema::create('_tb_conferencia', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('unidade_id')->index('idx_conferencia_unidade');
            $table->integer('material_id')->index('idx_conferencia_material');
            $table->integer('quantidade_conferida')->nullable();
            $table->enum('tipo', ['entrada', 'saida'])->nullable();
            $table->integer('usuario_id')->nullable()->index('usuario_id');
            $table->timestamp('data_conferencia')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_tb_conferencia');
    }
};
