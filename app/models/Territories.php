<?php

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class Territories extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'territories';

	/**
	 * Fillable table attributes
	 *
	 * @var array
	 */
	protected $fillable = array('territory_id', 'type_id', 'area_type_id', 'map_embed_id', 'keep_not_homes', 'notes');

	/**
	 * Set created_at date format
	 *
	 * @param  $value
	 * @return string
	 */
	public function getCreatedAtAttribute($value)
	{
		return Carbon::createFromTimestamp(strtotime($value))->format('m/d/Y');
	}

	public function scopeLabelAscending($query)
	{
		return $query->get()->sortBy('label');
	}

	public function publisher()
	{
		return $this->hasOne('User', 'id', 'publisher_id');
	}

	public function territoryType()
	{
		return $this->hasOne('TerritoriesTypes', 'id', 'type_id');
	}

	public function territoryAreaType()
	{
		return $this->hasOne('TerritoriesAreaTypes', 'id', 'type_id');
	}

	public function territoryNotes()
	{
		return $this->hasMany('TerritoriesSignInOut', 'territory_id', 'id')
					->select('signed_in', 'notes AS note')
					->whereNotNull('notes');
	}

	public function doNotCalls()
	{
		return $this->hasMany('DoNotCalls', 'territory_id', 'id');
	}

	public function history()
	{
		return $this->hasMany('TerritoriesSignInOut', 'territory_id', 'id')->orderBy('signed_out', 'DESC');
	}

	public function emailKey()
	{
		return $this->hasOne('TerritoriesSignOutKeys', 'territory_id', 'id');
	}

	public static function neverWorked($type)
	{
		switch($type)
		{
			case 'house':
				$type_id = 1;
				break;
			case 'business':
				$type_id = 2;
				break;
			case 'lwp':
				$type_id = 3;
		}

		return Territories::select('territories.id', 'territories.label', 'territories.type_id', 'territories.area_type_id', 'territories.map_embed_id', DB::raw('CASE WHEN tsio.signed_in IS NOT NULL THEN tsio.signed_in ELSE NULL END AS signed_in'))
							->leftJoin(DB::raw('(
								SELECT territory_id, MAX(signed_in) signed_in
								FROM `territories_sign_in_out`
								GROUP BY territory_id) tsio'), function($join)
							{
								$join->on('territories.id', '=', 'tsio.territory_id');
							})
							->whereNull('tsio.territory_id')
							->where('territories.type_id', '=', $type_id)
							->get();
	}

	public static function availableHouseToHouse()
	{
		$interval = Carbon::now()->subMonths(Options::get('territory_not_worked_interval_months'))->toDateTimeString();

		$h_never_worked = Territories::neverWorked('house');
		$h_available    = Territories::select('territories.id', 'territories.label', 'territories.type_id', 'territories.area_type_id', 'territories.map_embed_id', 'tsio.signed_in')
									->join(DB::raw('(
										SELECT territory_id, CASE WHEN MAX(signed_in IS NULL) = 0 THEN MAX(signed_in) ELSE NULL END AS signed_in
										FROM `territories_sign_in_out`
										GROUP BY territory_id) tsio'), function($join)
									{
										$join->on('territories.id', '=', 'tsio.territory_id');
									})
									->where('territories.type_id', '=', 1)
									->where('tsio.signed_in', '<', $interval)
									->whereNotNull('tsio.signed_in')
									->orderBy('tsio.signed_in', 'ASC')
									->take(Options::get('territory_amount_available_house') - $h_never_worked->count())
									->get();

		return $h_never_worked->merge($h_available);
	}

	public static function availableLetterWritingPhone()
	{
		$interval = Carbon::now()->subMonths(Options::get('territory_not_worked_interval_months'))->toDateTimeString();

		$lwp_never_worked = Territories::neverWorked('lwp');
		$lwp_limit        = ('0' == Options::get('territory_amount_available_lwp')) ? '99999' : Options::get
		('territory_amount_available_lwp');
		$lwp_available    = Territories::select('territories.id', 'territories.label', 'territories.type_id', 'territories.area_type_id', 'territories.map_embed_id', 'tsio.signed_in')
										->join(DB::raw('(
											SELECT territory_id, CASE WHEN MAX(signed_in IS NULL) = 0 THEN MAX(signed_in) ELSE NULL END AS signed_in
											FROM `territories_sign_in_out`
											GROUP BY territory_id) tsio'), function($join)
										{
											$join->on('territories.id', '=', 'tsio.territory_id');
										})
										->where('territories.type_id', '=', 3)
										->where('tsio.signed_in', '<', $interval)
										->whereNotNull('tsio.signed_in')
										->orderBy('tsio.signed_in', 'ASC')
										->take($lwp_limit - $lwp_never_worked->count())
										->get();

		return $lwp_never_worked->merge($lwp_available);
	}

	public static function availableBusiness()
	{
		$interval = Carbon::now()->subMonths(Options::get('territory_not_worked_interval_months'))->toDateTimeString();

		$b_never_worked = Territories::neverWorked('business');
		$b_limit        = ('0' == Options::get('territory_amount_available_business')) ? '99999' : Options::get('territory_amount_available_business');
		$b_available    = Territories::select('territories.id', 'territories.label', 'territories.type_id', 'territories.area_type_id', 'territories.map_embed_id', 'tsio.signed_in')
									->join(DB::raw('(
										SELECT territory_id, CASE WHEN MAX(signed_in IS NULL) = 0 THEN MAX(signed_in) ELSE NULL END AS signed_in
										FROM `territories_sign_in_out`
										GROUP BY territory_id) tsio'), function($join)
									{
										$join->on('territories.id', '=', 'tsio.territory_id');
									})
									->where('territories.type_id', '=', 2)
									->where('tsio.signed_in', '<', $interval)
									->whereNotNull('tsio.signed_in')
									->orderBy('tsio.signed_in', 'ASC')
									->take($b_limit - $b_never_worked->count())
									->get();

		return $b_never_worked->merge($b_available);
	}

	public function delete()
	{
		try
		{
			// remove territory history
			$this->history()->delete();

			// update do not call history (keep records but set territory_id to 0)
			$this->doNotCalls()->update(array('territory_id' => 0));

			return parent::delete();
		}
		catch (Exception $e)
		{
			return $e->getMessage();
		}
	}

}