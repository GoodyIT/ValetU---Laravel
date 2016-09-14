<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAddressParkinglotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parkinglots', function (Blueprint $table) {
            $table->string('address')->unique()->change();
            $table->index(['id', 'latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('parkinglots', function (Blueprint $table) {
            $table->dropUnique('address');
            $table->dropIndex('parkinglots_id_latitude_longitude_index');
        });
    }
}
