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
        Schema::create('_tb_armazenagem', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('sku', 100)->index('idx_armazenagem_sku');
            $table->integer('quantidade');
            $table->string('endereco', 50)->index('idx_armazenagem_endereco');
            $table->text('observacoes')->nullable();
            $table->integer('usuario_id')->index('usuario_id');
            $table->timestamp('data_armazenagem')->useCurrent();
            $table->integer('unidade_id')->index('idx_armazenagem_unidade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_tb_armazenagem');
    }
};
