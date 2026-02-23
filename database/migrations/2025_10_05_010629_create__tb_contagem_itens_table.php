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
        Schema::create('_tb_contagem_itens', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('codigo_material', 20)->index('fk_contagem_itens');
            $table->integer('quantidade');
            $table->integer('usuario_id')->nullable()->index('usuario_id');
            $table->integer('unidade_id')->nullable()->index('idx_paletes_unidade');
            $table->timestamp('data_contagem')->useCurrent();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_tb_contagem_itens');
    }
};
