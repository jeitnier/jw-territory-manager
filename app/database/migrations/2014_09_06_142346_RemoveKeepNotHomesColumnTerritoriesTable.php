<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveKeepNotHomesColumnTerritoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('territories', function($table) {
			$table->dropColumn('keep_not_homes');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('territories', function($table) {
			$table->integer('keep_not_homes')->after('map_embed_id')->default(1);
		});
	}

}
