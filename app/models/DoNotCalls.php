<?php

class DoNotCalls extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'do_not_calls';

	/**
	 * Fillable table attributes
	 *
	 * @var array
	 */
	protected $fillable = array('territory_id', 'first_name', 'last_name', 'address', 'city', 'zip', 'notes');

	public function scopeTerritoryAscending($query)
	{
		return $query->orderBy('territory_id', 'ASC');
	}

	public function territory()
	{
		return $this->hasOne('Territories', 'id', 'territory_id');
	}

}