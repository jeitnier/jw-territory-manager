<?php namespace TerritoryManager\Utility;

use Carbon\Carbon;

class Utility {

	/**
	 * Generates a username based on name.
	 *
	 * @param array $name
	 * @return string
	 */
	public function generateUsername(array $name)
	{
		$first_name = $name[0];
		$last_name  = $name[1];

		$username = strtolower(substr($first_name, 0, 1) . $last_name);

		// if username already exists, add a random string of numbers to the end
		if (\User::where('username', '=', $username)->get()->count() > 0)
		{
			$rand_num = '';

			for ($i = 0; $i < 4; $i++)
			{
				$rand_num .= mt_rand(0, 9);
			}

			$username .= $rand_num;
		}

		return $username;
	}

	/**
	 * Generate a random password.
	 *
	 * @return string
	 */
	public function generatePassword()
	{
		return str_random(8);
	}

	/**
	 * Set created_at date format.
	 *
	 * @param $value
	 * @return string
	 */
	public function formatDate($value)
	{
		if (NULL !== $value)
			return Carbon::createFromTimestamp(strtotime($value))->format('m/d/Y');

		return '';
	}

	/**
	 * Return due date of territory.
	 *
	 * @param $value
	 * @return string
	 */
	public function dueDate($value)
	{
		if (NULL !== $value)
			return Carbon::createFromTimestamp(strtotime($value))->addMonths(\Options::get('territory_due_date_months'))->format('m/d/Y');

		return '';
	}

	/**
	 * Return TRUE if today's date is > passed date.
	 *
	 * @param $value
	 * @return bool
	 */
	public function isOverdue($value)
	{
		if (Carbon::now() > Carbon::createFromTimestamp(strtotime($value))->addMonths(\Options::get('territory_due_date_months')))
			return TRUE;

		return FALSE;
	}

	/**
	 * Take the signed out date and find the amount overdue from today's date.
	 *
	 * @param $value
	 * @param string $format
	 * @return string
	 */
	public function overdueAmount($value, $format = 'days')
	{
		switch ($format)
		{
			case 'days':
				$diff = 'diffInDays';
				break;
			case 'months':
				$diff = 'diffInMonths';
				break;
			case 'years':
				$diff = 'diffInYears';
				break;
		}

		return Carbon::now()->$diff(
			Carbon::createFromTimestamp(strtotime($value))
				->addMonths(\Options::get('territory_due_date_months'))
			) .	" $format overdue";
	}

	/**
	 * Curl a URL.
	 *
	 * @param $url
	 * @return mixed|string
	 */
	public function curl($url)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);

		$response = curl_exec($ch);

		if (curl_errno($ch))
		{
			return curl_error($ch);
		}

		curl_close($ch);

		return $response;
	}

	/**
	 * XSS clean Input.
	 *
	 * @param $array
	 * @return array
	 */
	public function arrayStripTags($array)
	{
		$result = array();
		foreach ($array AS $key => $value)
		{
			$key = strip_tags($key);
			if (is_array($value))
			{
				$result[$key] = $this->arrayStripTags($value);
			}
			else
			{
				$result[$key] = strip_tags($value);
			}
		}

		return $result;
	}

	/**
	 * Check if string is real JSON
	 *
	 * @param $string
	 * @return bool
	 */
	public function isJson($string)
	{
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}

}