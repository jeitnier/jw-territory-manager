<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNotesColumnToTerritoriesSignInOutTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('territories_sign_in_out', function($table)
		{
			$table->longtext('notes')->nullable()->default(NULL)->after('campaign');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('territories_sign_in_out', function($table)
		{
			$table->dropColumn('notes');
		});
	}

}
