<?php namespace TerritoryManager\Options;

use Illuminate\Support\Facades\Facade;

class OptionsFacade extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'options'; }

}