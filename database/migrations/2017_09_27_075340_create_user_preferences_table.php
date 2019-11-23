<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('budget')->nullable();
            $table->string('location')->nullable();
            $table->string('epc')->nullable();
            $table->boolean('empty')->nullable();
            $table->string('property_type')->nullable();
            $table->boolean('has_bathroom')->nullable();
            $table->dateTime('avail_from')->nullable();
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
        Schema::dropIfExists('user_preferences');
    }
}
