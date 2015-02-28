<?php

class UserPasswords extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users_passwords';

	/**
	 * Fillable table attributes
	 *
	 * @var array
	 */
	protected $fillable = array('user_id', 'temp_password');

}