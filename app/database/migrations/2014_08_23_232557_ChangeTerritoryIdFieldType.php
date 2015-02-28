<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTerritoryIdFieldType extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('territories_sign_in_out', function($table) {
			$table->dropColumn('territory_id');
		});
		Schema::table('territories_sign_in_out', function($table) {
			$table->string('territory_id')->after('id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('territories_sign_in_out', function($table) {
			$table->dropColumn('territory_id');
		});
		Schema::table('territories_sign_in_out', function($table) {
			$table->integer('territory_id')->after('id');
		});
	}

}
