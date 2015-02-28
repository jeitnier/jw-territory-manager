<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DestroyUsersPasswordsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('users_passwords');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::create('users_passwords', function($table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->string('temp_password');
			$table->timestamps();
		});
	}

}
