<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpeaker extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Speaker_sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('session_id');
            $table->integer('speaker_id');

            $table->foreign('speaker_id')->references('id')->on('speakers');
            $table->foreign('session_id')->references('id')->on('sessions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Speaker_sessions');
    }
}
