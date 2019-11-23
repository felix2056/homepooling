<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('property_id');
            $table->integer('beds');
            $table->integer('occupants');
            $table->integer('price');
            $table->integer('deposit')->nullable();
            $table->integer('bills')->nullable();
            $table->boolean('has_bathroom');
            $table->dateTime('avail_from')->nullable();
            $table->dateTime('avail_to')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('rooms');
    }
}
