<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllModels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('packages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->longText('description');
            $table->string('price');
        });
        Schema::create('document_words', function (Blueprint $table) {
            $table->increment('doc_id');
            $table->integer('word_id')->unsigned();
            $table->float('weight', 7, 2);
            $table->foreign('word_id')->references('id')->on('word')->onDelete('cascade');
        });
        Schema::create('words', function (Blueprint $table) {
            $table->increments('id');
            $table->string('word');
        });
        Schema::create('idfs', function (Blueprint $table) {
            $table->integer('word_id')->unsigned();
            $table->float('idf', 7, 2);
            $table->foreign('word_id')->references('id')->on('word')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
