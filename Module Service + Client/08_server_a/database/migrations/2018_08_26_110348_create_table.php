<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 100);
            $table->string('password');
            $table->string('token')->nullable();
            $table->enum('role', ['ADMIN', 'USER']);
        });

        Schema::create('histories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('place_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('count')->default(1);

            $table->foreign('place_id')->references('id')->on('places')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('selections', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('from_place_id');
            $table->unsignedInteger('to_place_id');
            $table->unsignedInteger('count')->default(1);

            $table->foreign('from_place_id')->references('id')->on('places')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('to_place_id')->references('id')->on('places')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('selection_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('selection_id');
            $table->unsignedInteger('schedule_id');

            $table->foreign('selection_id')->references('id')->on('selections')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('schedule_id')->references('id')->on('schedules')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->foreign('from_place_id')->references('id')->on('places')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('to_place_id')->references('id')->on('places')->onUpdate('cascade')->onDelete('cascade');
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
