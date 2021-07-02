<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProtocolosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Protocolos', function (Blueprint $table) {
            $table->string('Numero_Protocolo')->primary();
            $table->string('Registro_ANS');
            $table->string('Codigo_Operadora');
            $table->string('CNPJ_Operadora');
            $table->string('Data_Emissao')->nullable();
            $table->string('Nome_Operadora');
            $table->string('Codigo_CNES');
            $table->string('Numero_Lote');
            $table->string('Nome_Contratado');
            $table->string('Data_Protocolo');
            $table->string('Codigo_Glosa_Protocolo')->nullable();
            $table->string('Codigo_Situacao_Protocolo');
            $table->string('Valor_Informado_Protocolo');
            $table->string('Valor_Processado_Protocolo');
            $table->string('Valor_Liberado_Protocolo');
            $table->string('Valor_Glose_Protocolo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Protocolos');
    }
}
