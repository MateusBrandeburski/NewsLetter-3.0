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
        Schema::create('corridas_df', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('distancia');
            $table->text('local');
            $table->string('valor');
            $table->string('horario');
            $table->text('inscricao')->nullable();
            $table->longText('img')->nullable(); // Para armazenar imagem em base64 ou URL
            $table->date('data_evento');
            $table->timestamp('data_cadastro')->useCurrent();
            $table->timestamp('data_atualizacao')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corridas_df');
    }
};
