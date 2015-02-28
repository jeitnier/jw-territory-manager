<?php

class TerritoriesSignOutKeys extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'territories_sign_out_keys';

	/**
	 * Fillable table attributes
	 *
	 * @var array
	 */
	protected $fillable = array('territory_id', 'key');

}