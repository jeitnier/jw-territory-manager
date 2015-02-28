<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignColumnTerritoriesSignInOutTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('territories_sign_in_out', function($table)
		{
			$table->string('campaign')->after('signed_in')->nullable()->default(NULL);
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
			$table->dropColumn('campaign');
		});
	}

}
