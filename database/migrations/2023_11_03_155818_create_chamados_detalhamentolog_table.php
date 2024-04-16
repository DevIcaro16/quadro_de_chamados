<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChamadosDetalhamentologTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chamados_detalhamentolog', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chamados_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->string('titulo');
            $table->string('conteudo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chamados_detalhamentolog');
    }
}
