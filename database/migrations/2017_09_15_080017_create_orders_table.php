<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
	public function up(){
		Schema::create('orders', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('property_id');
			$table->string('status');
			$table->string('type');
			$table->integer('remaining_msg_in');
			$table->boolean('early_access');
			$table->boolean('bumped');
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
        Schema::dropIfExists('orders');
    }
}
