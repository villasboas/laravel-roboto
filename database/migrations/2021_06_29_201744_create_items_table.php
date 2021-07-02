<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Items', function (Blueprint $table) {
            $table->string('Codigo_Item')->primary();
            $table->string('Numero_Guia_Prestador')->index();
            $table->string('Data_Realizacao');
            $table->string('Tabela');
            $table->string('Codigo_Procedimento');
            $table->string('Descricao');
            $table->string('Grau_Participacao')->nullable();
            $table->string('Valor_Informado');
            $table->string('Quantidade_Executada');
            $table->string('Valor_Processado');
            $table->string('Valor_Liberado');
            $table->string('Valor_Glosa')->nullable();
            $table->string('Codigo_Glosa')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Items');
    }
}
