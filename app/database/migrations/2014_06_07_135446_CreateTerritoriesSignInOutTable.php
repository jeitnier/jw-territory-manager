<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTerritoriesSignInOutTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('territories_sign_in_out', function($table)
		{
			$table->increments('id');
			$table->integer('territory_id');
			$table->integer('publisher_id');
			$table->timestamp('signed_out')->nullable()->default(NULL);
			$table->timestamp('signed_in')->nullable()->default(NULL);
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
		Schema::dropIfExists('territories_sign_in_out');
	}

}
