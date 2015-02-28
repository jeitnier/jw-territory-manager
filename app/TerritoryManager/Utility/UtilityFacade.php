<?php namespace TerritoryManager\Utility;

use Illuminate\Support\Facades\Facade;

class UtilityFacade extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'utility'; }

}