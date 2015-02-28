<?php namespace TerritoryManager\Options;

use Illuminate\Support\ServiceProvider;

class OptionsServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind('options', function()
		{
			return new Options;
		});
	}

}