<?php

class Settings extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'settings';

	/**
	 * Disable model timestamps.
	 *
	 * @var bool
	 */
	public $timestamps = FALSE;

}