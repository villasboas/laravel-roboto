<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Guias', function (Blueprint $table) {
            $table->string('Numero_Guia_Prestador')->primary();
            $table->string('Numero_Protocolo')->index();
            $table->string('Numero_Guia_Prestadora');
            $table->string('Senha')->nullable();
            $table->string('Nome_Beneficiario');
            $table->string('Numero_Carteira');
            $table->string('Data_Inicio_Faturamento');
            $table->string('Hora_Inicio_Faturamento')->nullable();
            $table->string('Data_Fim_Faturamento')->nullable();
            $table->string('Hora_Fim_Faturamento')->nullable();
            $table->string('Codigo_Glosa_Guia');
            $table->string('Codigo_Situacao_Guia');
            $table->string('Valor_Informado_Guia');
            $table->string('Valor_Processado_Guia');
            $table->string('Valor_Liberado_Guia');
            $table->string('Valor_Glosa_Guia');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Guias');
    }
}
