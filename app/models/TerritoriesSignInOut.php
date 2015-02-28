<?php

use Carbon\Carbon;

class TerritoriesSignInOut extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'territories_sign_in_out';

	/**
	 * Fillable table attributes.
	 *
	 * @var array
	 */
	protected $fillable = ['territory_id', 'publisher_id', 'signed_out', 'signed_in'];

	public function getDates()
	{
		return ['signed_out', 'signed_in'];
	}

	/**
	 * Return a matching territory.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function territory()
	{
		return $this->hasOne('Territories', 'id', 'id');
	}

	/**
	 * Return a matching publisher.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function publisher()
	{
		return $this->hasOne('User', 'id', 'publisher_id');
	}

	/**
	 * Get the status of the territories condition.
	 *
	 * @param $id
	 * @return string
	 */
	public static function status($id)
	{
		$territories_sign_in_out = TerritoriesSignInOut::where('territory_id', '=', $id)
														->orderBy('updated_at', 'DESC')
														->first();

		if (NULL !== $territories_sign_in_out)
		{
			if (NULL !== $territories_sign_in_out->signed_out AND NULL === $territories_sign_in_out->signed_in)
			{
				$status = '<span class="text-warning">In Progress</span>';
			}
			else
			{
				$status = '<span class="text-info">Not In Progress</span>';
			}
		}
		else
		{
			$status = '<span class="text-danger">Never Worked</span>';
		}

		return $status;
	}

	/**
	 * Return the publisher who is working the territory.
	 *
	 * @param        $id
	 * @param string $prefix
	 * @return null|string
	 */
	public static function whoIsWorking($id, $prefix = ' - ')
	{
		$who_is_working = TerritoriesSignInOut::select(
				'u.id',
				'u.first_name',
				'u.last_name'
			)
			->where('territories_sign_in_out.territory_id', '=', $id)
			->whereNull('territories_sign_in_out.signed_in')
			->join('users AS u', 'territories_sign_in_out.publisher_id', '=', 'u.id')
			->first();

		if (NULL !== $who_is_working)
			return $prefix . link_to_route(
				'admin.publishers.show',
				NULL !== $who_is_working ? $who_is_working->first_name . ' ' . $who_is_working->last_name : NULL,
				NULL !== $who_is_working ? [$who_is_working->id] : NULL
			);

		return NULL;
	}

	/**
	 * Return the publisher who last worked the territory.
	 *
	 * @param $id
	 * @return mixed|static
	 */
	public static function lastWorked($id)
	{
		return TerritoriesSignInOut::select('signed_in')
			->where('territory_id', '=', $id)
			->whereNotNull('signed_out')
			->whereNotNull('signed_in')
			->orderBy('updated_at', 'DESC')
			->first();
	}

	/**
	 * Get all Territories that are overdue.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public static function overdue()
	{
		return TerritoriesSignInOut::select(
			't.id',
			't.label',
			'territories_sign_in_out.signed_out',
			'territories_sign_in_out.publisher_id'
		)
		->join(
			'territories AS t',
			'territories_sign_in_out.territory_id',
			'=',
			't.id'
		)
		->whereNull('territories_sign_in_out.signed_in')
		->where(
			'territories_sign_in_out.signed_out',
			'<',
			DB::raw('DATE_SUB(NOW(), INTERVAL ' . \Options::get('territory_due_date_months') . ' MONTH)')
		)
		->orderBy('t.label')
		->get();
	}

	/**
	 * Check if a territory is overdue.
	 *
	 * @param $date
	 * @return bool
	 */
	public static function isOverdue($date)
	{
		return Carbon::now() >= Carbon::createFromTimestamp(strtotime($date))->addMonths(\Options::get('territory_due_date_months'));
	}

	/**
	 * Get territories due soon or overdue.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public static function dueSoon()
	{
		// add due date days to "soon" days to get total interval days
		$interval = Carbon::now()
							->diffInDays(Carbon::now()->subMonths(\Options::get('territory_due_date_months')))
							- \Options::get('territory_soon_days');

		return TerritoriesSignInOut::select(
			't.id',
			't.label',
			'territories_sign_in_out.territory_id',
			'territories_sign_in_out.signed_out'
		)
		->join(
			'territories AS t',
			'territories_sign_in_out.territory_id',
			'=',
			't.id'
		)
		->where(
			'territories_sign_in_out.signed_out',
			'<',
			DB::raw("DATE_SUB(NOW(), INTERVAL $interval DAY)")
		)
		->whereNull('territories_sign_in_out.signed_in')
		->orderBy('territories_sign_in_out.signed_out')
		->get();
	}

	/**
	 * Get all territories that are overdue by at least one year.
	 *
	 * @return mixed
	 */
	public static function overOneYear()
	{
		$h_never_worked   = Territories::neverWorked('house');
		$lwp_never_worked = Territories::neverWorked('lwp');
		$b_never_worked   = Territories::neverWorked('business');

		$never_worked = $h_never_worked->merge($lwp_never_worked);
		$never_worked = $never_worked->merge($b_never_worked);

		$territories = Territories::select(
			'territories.id',
			'territories.label',
			'tsio.signed_in'
		)
		->join(DB::raw('(
			SELECT territory_id, CASE WHEN signed_in IS NOT NULL THEN MAX(signed_in) ELSE NULL END AS signed_in
			FROM `territories_sign_in_out`
			GROUP BY territory_id) tsio'), function($join)
			{
				$join->on('territories.id', '=', 'tsio.territory_id');
			}
		)
		->where('tsio.signed_in', '<=', Carbon::now()->subYear())
		->orderBy('territories.label')
		->get();

		return $never_worked->merge($territories)->sortBy('label');
	}

	/**
	 * Check if the territory is being worked.
	 *
	 * @param $territory_id
	 * @return \Illuminate\Database\Eloquent\Model|null|static
	 */
	public static function beingWorked($territory_id)
	{
		return TerritoriesSignInOut::where('territory_id', '=', $territory_id)
									->whereNull('signed_in')
									->first();
	}

	/**
	 * Check if territory is currently signed out.
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public static function currentlySignedOut()
	{
		return TerritoriesSignInOut::select(
				't.id',
				't.label',
				'territories_sign_in_out.signed_out',
				'territories_sign_in_out.publisher_id'
			)
			->join(
				'territories AS t',
				'territories_sign_in_out.territory_id',
				'=',
				't.id'
			)
			->whereNull('territories_sign_in_out.signed_in')
			->orderBy('territories_sign_in_out.signed_out')
			->get();
	}

	/**
	 * Return territories completed within an interval.
	 *
	 * @param $interval
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public static function completedWithin($interval)
	{
		switch ($interval)
		{
			case 'week':
				$start = 'startOfWeek';
				break;
			case 'month':
				$start = 'startOfMonth';
				break;
			case 'year':
				$start = 'startOfYear';
				break;
		}

		return TerritoriesSignInOut::join(
				'territories AS t',
				'territories_sign_in_out.territory_id',
				'=',
				't.id'
			)
			->whereBetween('territories_sign_in_out.signed_in', [Carbon::now()->$start(), Carbon::now()])
			->orderBy('t.label')
			->get();
	}

}