<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalhamentosLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalhamentosLogs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chamado_id')->nullable();
            $table->foreign('chamado_id')->references('id')->on('chamados')->nullable();
            $table->string('titulo');
            $table->string('conteudo');
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalhamentosLogs');
    }
}
