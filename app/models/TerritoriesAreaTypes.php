<?php

class TerritoriesAreaTypes extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'territories_area_types';

	/**
	 * Fillable table attributes
	 *
	 * @var array
	 */
	protected $fillable = array('name', 'label');

}