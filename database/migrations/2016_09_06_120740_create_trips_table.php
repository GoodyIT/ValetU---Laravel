<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
             $table->engine = 'InnoDB';
            $table->collation = 'utf8_unicode_ci';
            
            $table->increments('id');
            $table->integer('parkinglot_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('request')->unsigned(); // open, pending, closed(fare)
            $table->text('photourl');
            $table->text('comment');
            $table->integer('star')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('parkinglot_id')->references('id')->on('parkinglots');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
         Schema::drop('trips');
    }
}
