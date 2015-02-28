<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	public function territoriesSignedOut()
	{
		return $this->hasMany('TerritoriesSignInOut', 'publisher_id', 'id')
					->whereNotNull('signed_out')
					->whereNull('signed_in')
					->join('territories AS t', 'territories_sign_in_out.territory_id', '=', 't.id')
					->orderBy('t.label', 'ASC');
	}

	public function history()
	{
		return $this->hasMany('TerritoriesSignInOut', 'publisher_id', 'id')
					->select(['*', DB::raw('signed_in IS NULL AS sortOrderNull')])
					->join('territories AS t', 'territories_sign_in_out.territory_id', '=', 't.id')
					->orderBy('sortOrderNull', 'DESC')
					->orderBy('signed_in', 'DESC')
					->orderBy('t.label', 'DESC');
	}

	public function group()
	{
		return $this->hasOne('UserGroups');
	}

	public function delete()
	{
		try
		{
			// delete user group
			$this->group()->delete();

			// update territory history (keep records but set publisher_id to 0)
			TerritoriesSignInOut::where('publisher_id', '=', $this->id)->update(array('publisher_id' => 0));

			return parent::delete();
		}
		catch (Exception $e)
		{
			return $e->getMessage();
		}
	}

}