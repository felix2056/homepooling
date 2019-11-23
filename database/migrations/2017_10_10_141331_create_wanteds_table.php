<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWantedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wanteds', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('location');
            $table->string('lat');
            $table->string('long');
            $table->integer('distance');
            $table->string('price_range');
            $table->dateTime('avail_from');
            $table->dateTime('avail_to');
            $table->boolean('empty');
            $table->boolean('single');
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
        Schema::dropIfExists('wanteds');
    }
}
