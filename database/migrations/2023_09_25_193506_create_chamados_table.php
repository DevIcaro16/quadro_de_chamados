<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChamadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('chamados', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('cliente_id');
        $table->foreign('cliente_id')->references('id')->on('clientes');
        $table->string('cliente')->nullable();
        $table->string('tipo')->nullable();
        $table->boolean('status')->nullable();
        $table->string('descrição')->nullable();
        $table->string('titulo')->nullable();
        $table->string('data_chamado')->nullable();
        $table->string('solucao')->nullable();
        $table->string('data_solucao')->nullable();
        $table->unsignedBigInteger('user_id')->nullable();
        $table->foreign('user_id')->references('id')->on('users')->nullable();
        $table->string('contato')->nullable();
        $table->string('nome_contato')->nullable();
        $table->string('local')->nullable();
        $table->string('prioridade')->nullable();
        $table->string('detalhamento')->nullable();
        $table->string('img',3000)->nullable();
        // $table->timestamps();


    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chamados');
    }
}