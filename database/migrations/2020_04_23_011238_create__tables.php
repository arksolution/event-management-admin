<?php
//
//use Illuminate\Support\Facades\Schema;
//use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Database\Migrations\Migration;
//
//class CreateTables extends Migration
//{
//    /**
//     * Run the migrations.
//     *
//     * @return void
//     */
//    public function up()
//    {
//        Schema::create('Event_ratings', function (Blueprint $table) {
//            $table->increments('id');
//            $table->integer('event_id');
//            $table->integer('attendee_id');
//            $table->tinyInteger('rating');
//            $table->dateTime('creation_date');
//            $table->string('comment');
//
//            $table->foreign('event_id')->references('id')->on('Events');
//            $table->foreign('attendee_id')->references('id')->on('Attendees');
//        });
//        Schema::create('Session_ratings', function (Blueprint $table) {
//            $table->increments('id');
//            $table->integer('session_id');
//            $table->integer('attendee_id');
//            $table->tinyInteger('rating');
//            $table->dateTime('creation_date');
//
//            $table->foreign('session_id')->references('id')->on('Sessions');
//            $table->foreign('attendee_id')->references('id')->on('Attendees');
//        });
//    }
//
//    /**
//     * Reverse the migrations.
//     *
//     * @return void
//     */
//    public function down()
//    {
//        Schema::dropIfExists('Event_ratings');
//        Schema::dropIfExists('Session_ratings');
//    }
//}
