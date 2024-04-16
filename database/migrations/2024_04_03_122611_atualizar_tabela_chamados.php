<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AtualizarTabelaChamados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chamados', function (Blueprint $table) {
           
            $table->string('titulo')->nullable();
            $table->string('nome_contato')->nullable();
            $table->string('img',3000)->nullable();
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chamados', function (Blueprint $table) {
            // Reverta as alterações feitas na função up()
            $table->dropColumn('titulo');
            $table->dropColumn('nome_contato');
            $table->dropColumn('img',3000);
        });
    }
}
