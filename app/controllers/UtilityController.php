<?php

class UtilityController extends BaseController {

	/**
	 * JSON endpoint to list all account maps.
	 * (http://api.tiles.mapbox.com/v3/{username}/maps.json)
	 *
	 * @var string
	 */
	protected $map_list_uri = '';

	/**
	 * Show territory resolver view.
	 *
	 * @return View
	 */
	public function territoryResolver()
	{
		return View::make('utility.territory-resolver');
	}

	/**
	 * Show all maps.
	 *
	 * @return View
	 */
	public function allMaps()
	{
		return View::make('utility.all-maps');
	}

}