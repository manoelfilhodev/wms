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
        Schema::create('_tb_expedicao', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('unidade_id')->index('idx_expedicao_unidade');
            $table->string('pedido_numero', 50)->index('idx_expedicao_pedido');
            $table->string('cliente', 100)->nullable();
            $table->enum('status', ['pendente', 'em_separacao', 'expedido'])->nullable()->default('pendente');
            $table->timestamp('data_expedicao')->useCurrent();
            $table->integer('usuario_id')->nullable()->index('usuario_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_tb_expedicao');
    }
};
