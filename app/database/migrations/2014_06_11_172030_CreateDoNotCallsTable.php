<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoNotCallsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('do_not_calls', function($table)
		{
			$table->increments('id');
			$table->integer('territory_id');
			$table->string('first_name')->nullable()->default(NULL);
			$table->string('last_name')->nullable()->default(NULL);
			$table->string('address')->nullable()->default(NULL);
			$table->string('city')->nullable()->default(NULL);
			$table->string('zip')->nullable()->default(NULL);
			$table->longtext('notes')->nullable()->default(NULL);
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
		Schema::dropIfExists('do_not_calls');
	}

}
